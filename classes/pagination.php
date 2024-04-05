<?php
class Pagination {
    public static function generatePagination($totalItems, $itemsPerPage, $currentPage, $urlPrefix) {
        $totalPages = ceil($totalItems / $itemsPerPage);
        if ($totalPages > 1) {
            // Tính toán trang bắt đầu và trang kết thúc để hiển thị
            $startPage = max(1, min($totalPages - 2, $currentPage - 1));
            $endPage = min($totalPages, $startPage + 2);

            // Hiển thị nút "Trang đầu"
            if ($startPage > 1) {
                echo '<a href="' . $urlPrefix . '&page=1">&laquo;</a>';
            }

            // Hiển thị các trang
            for ($i = $startPage; $i <= $endPage; $i++) {
                $activeClass = ($i == $currentPage) ? 'active' : '';
                echo '<a href="' . $urlPrefix . '&page=' . $i . '" class="' . $activeClass . '">' . $i . '</a>';
            }

            // Hiển thị nút "Trang cuối"
            if ($endPage < $totalPages) {
                echo '<a href="' . $urlPrefix . '&page=' . $totalPages . '">&raquo;</a>';
            }
        }
    }
}
?>