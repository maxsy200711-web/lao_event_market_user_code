<?php
if (!function_exists('admin_pagination')) {
    function admin_pagination(int $total, int $perPage, int $page): void {
        $pages = (int)ceil($total / max(1, $perPage));
        if ($pages < 2) return;
        $params = $_GET;
        echo '<nav class="admin-pagination" aria-label="Pagination">';
        for ($i = 1; $i <= $pages; $i++) {
            $params['page'] = $i;
            $url = '?' . http_build_query($params);
            echo '<a class="' . ($i === $page ? 'current' : '') . '" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $i . '</a>';
        }
        echo '</nav>';
    }
}
