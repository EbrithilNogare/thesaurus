<?php
function loadTreeViewByParentID($parentID){
    $hiearchyTree = [];
    $conn = connectToDB();

    $sql = <<<SQL
        SELECT ID, label 
        FROM words
        LEFT JOIN translations ON translations.word_id = words.ID
        WHERE parent = '$parentID' AND language = 'en'
        ORDER BY translations.label ASC	
SQL;

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hiearchyTree[$row["ID"]] = $row["label"];
        }
    }

    $conn->close();
    return $hiearchyTree;
}