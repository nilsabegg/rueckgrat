<?php

class Votable {

    public function upVote($userId) {

        $query = 'INSERT INTO ' . $this::table . '_vote SET ';
        $valuesInsert = $this::table . '_id=?, user_id=?, vote=-1, created_at = FROM_UNIXTIME(' . time() . '), updated_at = FROM_UNIXTIME(' . time() . ')';
        $valuesUpdate = $this::table . '_id=?, user_id=?, vote=-1, updated_at = FROM_UNIXTIME(' . time() . ')';
        $query = $query . $valuesInsert . ' ON DUPLICATE KEY UPDATE ' . $valuesUpdate;
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute(array($this->getId(), $userId));

    }

    public function downVote($userId) {

        $query = 'INSERT INTO ' . $this::table . '_vote SET ';
        $valuesInsert = $this::table . '_id=?, user_id=?, vote=-1, created_at = FROM_UNIXTIME(' . time() . '), updated_at = FROM_UNIXTIME(' . time() . ')';
        $valuesUpdate = $this::table . '_id=?, user_id=?, vote=-1, updated_at = FROM_UNIXTIME(' . time() . ')';
        $query = $query . $valuesInsert . ' ON DUPLICATE KEY UPDATE ' . $valuesUpdate;
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute(array($this->getId(), $userId));

    }

    public function voteResult() {

        $query = 'SELECT SUM(vote) FROM ' . $this::table . '_vote WHERE ' . $this::table . '_id=?;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute(array($this->getId()));

    }
}
