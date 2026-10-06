<?php
if (!function_exists('organizer_pagination')) {
    function organizer_pagination(int $total, int $perPage, int $page, string $parameter = 'page'): void
    {
        $pages = (int)ceil($total / max(1, $perPage));
        if ($pages < 2) return;

        $params = $_GET;
        echo '<nav class="org-pagination" aria-label="Pagination">';
        for ($i = 1; $i <= $pages; $i++) {
            $params[$parameter] = $i;
            $url = '?' . http_build_query($params);
            echo '<a class="' . ($i === $page ? 'current' : '') . '" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $i . '</a>';
        }
        echo '</nav>';
    }
}
