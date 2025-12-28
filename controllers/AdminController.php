<?php

class AdminController
{
    /* =====================================================
       GET /api/v1/admin/dashboard
    ===================================================== */
    public function dashboard()
    {
        authRequired();

        $db = db();

        // 1️⃣ Total jobs
        $totalJobs = (int)$db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();

        // 2️⃣ Jobs added today
        $todayJobs = (int)$db->query("
            SELECT COUNT(*)
            FROM jobs
            WHERE DATE(created_at) = CURDATE()
        ")->fetchColumn();

        // 3️⃣ Jobs added this month
        $monthJobs = (int)$db->query("
            SELECT COUNT(*)
            FROM jobs
            WHERE MONTH(created_at) = MONTH(CURDATE())
            AND YEAR(created_at) = YEAR(CURDATE())
        ")->fetchColumn();

        // 4️⃣ Top companies
        $topCompanies = $db->query("
            SELECT company_name, COUNT(*) AS total
            FROM jobs
            GROUP BY company_name
            ORDER BY total DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        // 5️⃣ Latest jobs
        $latestJobs = $db->query("
            SELECT id, title, company_name, location, created_at
            FROM jobs
            ORDER BY created_at DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        // ✅ DATA FIRST, MESSAGE SECOND
        successResponse(
            [
                "stats" => [
                    "total_jobs" => $totalJobs,
                    "today_jobs" => $todayJobs,
                    "this_month_jobs" => $monthJobs
                ],
                "top_companies" => $topCompanies,
                "latest_jobs" => $latestJobs
            ],
            "Dashboard data fetched successfully"
        );
    }

}
