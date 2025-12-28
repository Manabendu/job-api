<?php

class JobController
{
    /* =====================================================
       GET /api/v1/jobs
       Search + Filters + Pagination
    ===================================================== */
    public function index()
    {
        $db = db();

        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, min(50, (int)($_GET['limit'] ?? 10)));
        $offset = ($page - 1) * $limit;

        $values = [];
        $where = "";

        /* 🔍 SEARCH */
        if (!empty($_GET['search'])) {
            $search = "%" . trim($_GET['search']) . "%";

            $where = "
                WHERE (
                    j.title LIKE ?
                    OR j.company_name LIKE ?
                    OR j.location LIKE ?
                    OR EXISTS (
                        SELECT 1
                        FROM job_skills js
                        WHERE js.job_id = j.id
                        AND js.point LIKE ?
                    )
                )
            ";

            $values = [$search, $search, $search, $search];
        }

        $sql = "
            SELECT SQL_CALC_FOUND_ROWS j.*
            FROM jobs j
            $where
            ORDER BY j.created_at DESC
            LIMIT $limit OFFSET $offset
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($values);

        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = (int)$db->query("SELECT FOUND_ROWS()")->fetchColumn();

        successResponse(
            [
                "jobs" => $jobs,
                "pagination" => [
                    "page" => $page,
                    "limit" => $limit,
                    "total" => $total
                ]
            ],
            "Jobs fetched successfully"
        );
    }


    /* =====================================================
       GET /api/v1/jobs/{id}
       Full job details
    ===================================================== */
    public function show($jobId)
    {
        $db = db();

        $stmt = $db->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$jobId]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            errorResponse(
                404,
                "Job not found"
            );
        }
            // Fetch bullet sections
            $descriptions = $this->getBullets("job_descriptions", $jobId);
            $responsibilities = $this->getBullets("job_responsibilities", $jobId);
            $qualifications = $this->getBullets("job_qualifications", $jobId);
            $skills = $this->getBullets("job_skills", $jobId);

            successResponse(
                [
                    "job" => $job,
                    "descriptions" => $descriptions,
                    "responsibilities" => $responsibilities,
                    "qualifications" => $qualifications,
                    "skills" => $skills
                ],
                "Job details fetched successfully"
            );
    }

    /* =====================================================
       POST /api/v1/jobs
    ===================================================== */
    public function store()
    {
        authRequired();

        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['title']) ||
            empty($data['location']) ||
            empty($data['company_name'])
        ) {
            errorResponse(400,"VALIDATION_ERROR", "Required fields missing");
        }

        $db = db();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("
                INSERT INTO jobs
                (title, location, company_name, company_logo, salary_range, experience)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['title'],
                $data['location'],
                $data['company_name'],
                $data['company_logo'] ?? null,
                $data['salary_range'] ?? null,
                $data['experience'] ?? null
            ]);

            $jobId = $db->lastInsertId();

            $this->insertBullets($db, "job_descriptions", $jobId, $data['descriptions'] ?? []);
            $this->insertBullets($db, "job_responsibilities", $jobId, $data['responsibilities'] ?? []);
            $this->insertBullets($db, "job_qualifications", $jobId, $data['qualifications'] ?? []);
            $this->insertBullets($db, "job_skills", $jobId, $data['skills'] ?? []);

            $db->commit();
            successResponse(201, ["message" => "Job created", "job_id" => $jobId]);

        } catch (Exception $e) {
            $db->rollBack();
            errorResponse(500, "SERVER_ERROR", "Something went wrong");
        }
    }

    /* =====================================================
       HELPERS
    ===================================================== */
    private function getBullets($table, $jobId)
    {
        $stmt = db()->prepare("
            SELECT point FROM $table
            WHERE job_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->execute([$jobId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function insertBullets($db, $table, $jobId, $items)
    {
        if (!$items) return;

        $stmt = $db->prepare("
            INSERT INTO $table (job_id, point, sort_order)
            VALUES (?, ?, ?)
        ");

        foreach ($items as $i => $text) {
            $stmt->execute([$jobId, $text, $i + 1]);
        }
    }


    /* =====================================================
    PUT /api/v1/jobs/{id}
    Full replace
    ===================================================== */
    public function update($jobId)
    {
        authRequired();

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            errorResponse(400, "VALIDATION_ERROR","Invalid payload");
        }

        $db = db();

        try {
            $db->beginTransaction();

            // 1️⃣ Check job exists
            $check = $db->prepare("SELECT id FROM jobs WHERE id = ?");
            $check->execute([$jobId]);
            if (!$check->fetch()) {
                errorResponse(404,  "Job not found");
            }

            // 2️⃣ Update main job
            $stmt = $db->prepare("
                UPDATE jobs SET
                title = ?,
                location = ?,
                company_name = ?,
                company_logo = ?,
                salary_range = ?,
                experience = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $data['title'],
                $data['location'],
                $data['company_name'],
                $data['company_logo'] ?? null,
                $data['salary_range'] ?? null,
                $data['experience'] ?? null,
                $jobId
            ]);

            // 3️⃣ Delete old bullet data
            $tables = [
                "job_descriptions",
                "job_responsibilities",
                "job_qualifications",
                "job_skills"
            ];

            foreach ($tables as $table) {
                $db->prepare("DELETE FROM $table WHERE job_id = ?")
                ->execute([$jobId]);
            }

            // 4️⃣ Insert new bullet data
            $this->insertBullets($db, "job_descriptions", $jobId, $data['descriptions'] ?? []);
            $this->insertBullets($db, "job_responsibilities", $jobId, $data['responsibilities'] ?? []);
            $this->insertBullets($db, "job_qualifications", $jobId, $data['qualifications'] ?? []);
            $this->insertBullets($db, "job_skills", $jobId, $data['skills'] ?? []);

            $db->commit();
            successResponse(200, "Job updated successfully");

        } catch (Exception $e) {
            $db->rollBack();
            errorResponse(500, "SERVER_ERROR", "Something went wrong");
        }
    }


    /* =====================================================
    PATCH /api/v1/jobs/{id}
    Partial update
    ===================================================== */
    public function patch($jobId)
    {
        authRequired();

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            errorResponse(400,"VALIDATION_ERROR", "Empty PATCH body");
        }

        $db = db();

        // 1️⃣ Check job exists
        $check = $db->prepare("SELECT id FROM jobs WHERE id = ?");
        $check->execute([$jobId]);
        if (!$check->fetch()) {
            errorResponse(404,  "Job not found");
        }

        /* =========================
        Update main job fields
        ========================= */
        $fields = [];
        $values = [];

        $allowed = [
            "title", "location", "company_name",
            "company_logo", "salary_range", "experience"
        ];

        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }

        if ($fields) {
            $values[] = $jobId;
            $sql = "UPDATE jobs SET " . implode(", ", $fields) . " WHERE id = ?";
            $db->prepare($sql)->execute($values);
        }

        /* =========================
        Update bullet sections
        ========================= */
        $map = [
            "descriptions" => "job_descriptions",
            "responsibilities" => "job_responsibilities",
            "qualifications" => "job_qualifications",
            "skills" => "job_skills"
        ];

        foreach ($map as $key => $table) {
            if (isset($data[$key])) {
                $db->prepare("DELETE FROM $table WHERE job_id = ?")
                ->execute([$jobId]);
                $this->insertBullets($db, $table, $jobId, $data[$key]);
            }
        }

        successResponse(200, "Job updated (partial)");
    }


    /* =====================================================
    DELETE /api/v1/jobs/{id}
    ===================================================== */
    public function destroy($jobId)
    {
        authRequired();

        $stmt = db()->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->execute([$jobId]);

        if ($stmt->rowCount() === 0) {
            errorResponse(404,"Job not found");
        }

        successResponse(200, "Job deleted successfully");
    }

}
