<?php

namespace Rueckgrat\Model\Behaviour;
/**
 *
 */
class Taggable {

    /**
     * seperator
     *
     * Holds the seperator for the tags string.
     *
     * @var string
     */
    protected $seperator = ',';

    /**
     * addTag
     *
     * Adds a tag to the Model.
     * This Method saves the tag when its
     * called.
     *
     * @access public
     * @param string $tagName
     * @return void
     */
    public function addTag($tagName) {

        $tag = new TagModel();
        $tag->setName($tagName);
        $tagId = $tag->save();
        $table = $this::table . '_tag';
        $taggedId = $this->getId();
        $taggedColumn = $this::table . '_id';
        $query = 'INSERT INTO ' . $table . ' SET tag_id=?, ' . $taggedColumn . '=?;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute(array($tagId, $taggedId));

    }

    /**
     * addTags
     *
     * Adds multiple tags to the Model.
     * This Method saves the taga when its
     * called.
     * Pass either an array or a $seperator
     * seperated string
     *
     * @param string|mixed $tagNames
     * @return void
     */
    public function addTags($tagNames) {

        if (is_array($tagNames) == false) {
            $rawTags = explode($this->seperator, $tagNames);
            $tags = array();
        }
        foreach ($tags as $tag) {
            $this->addTag(trim($tag));
        }

    }

    /**
     * getTags
     *
     * Returns all tags for the model.
     *
     * @return mixed
     */
    public function getTags() {

        $table = $this::table . '_tag';
        $taggedId = $this->getId();
        $taggedColumn = $this::table . '_id';
        $query = 'SELECT tag.id, tag.name FROM ' . $table . ' LEFT JOIN tag ON table.tag_id=tag.id WHERE ' .$taggedColumn . '=?;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute(array($taggedId));
        $tags = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $this->createObjects($tags);

    }

    /**
     * getAllTags
     *
     * Returns all tags.
     *
     * @return mixed
     */
    public function getAllTags() {

        $query = 'SELECT * FROM tag;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute();
        $tags = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $this->createObjects($tags);

    }

    public function getByTag($tagName) {

        $query = 'SELECT ' . $this::table . '.* FROM ' . $this::table . '_tag LEFT JOIN tag ON tag_id=tag.id LEFT JOIN ' . $this::table . ' ON ' . $this::table . '_id=' . $this::table . '.id WHERE tag.name=?;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute($tagName);

    }

}
