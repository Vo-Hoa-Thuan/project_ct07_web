<? function changeType($Type) {
    $typeMappings = array(
        "Lắc tay" => "bangles",
        "Đồng hồ" => "watch",
        "Nhẫn" => "ring",
        "Dây cổ" => "chain"
    );

    return $typeMappings[$Type] ?? $Type;
}
?>