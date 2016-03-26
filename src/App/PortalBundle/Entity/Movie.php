<?php

namespace App\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Tests\Fixtures\SingleIntIdEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

use App\CoreBundle\Traits\Entity\TraitDatetime;
use App\CoreBundle\Traits\Entity\TraitSimple;
use App\CoreBundle\Traits\Entity\TraitEnabled;
use App\CoreBundle\Traits\Entity\Interfaces\TraitDatetimeInterface;
use App\CoreBundle\Traits\Entity\Interfaces\TraitSimpleInterface;
use App\CoreBundle\Traits\Entity\Interfaces\TraitEnabledInterface;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="movie")
 * @ORM\Entity(repositoryClass="App\PortalBundle\Repository\MovieRepository")
 */
class Movie implements TraitDatetimeInterface, TraitSimpleInterface, TraitEnabledInterface
{
    use TraitDatetime;

    use TraitSimple;

    use TraitEnabled;

    private static $likeFieds = ['title', 'description'];
    private static $likeFieldsSearchForm = ['title', 'description', 'releaseDateFrom', 'releaseDateTo'];
    private static $collectionFields = ['hashTags', 'actors'];
    private static $objectFields = ['category'];
    private static $managerCollectionMapping =
        [
            'actors' => 'actor',
            'hashTags' => 'hashTag',
        ];

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 3)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * nullable=false to prevent a movie from not having a category
     * notBlank forces the validation form to raise an exception if no category is selected
     * no remove annotation otherwise if a category would be deleted, all associated movies would be deleted too
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * onDelete CASCADE so when an actor is deleted, the association is removed from database
     * @ORM\ManyToMany(targetEntity="Actor")
     * @ORM\JoinTable(name="movie_actor",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $actors;

    /**
     * persist => when the movie form is submitted, the hashTags are persisted
     * no remove annotation here because when a hashTag is deleted in a movie form, associated hashTags are removed from database in UpdateMovieFormHandlerStrategy handle method
     * orphanRemoval=true => when a movie is deleted, associated hashTags are removed from database
     * @ORM\OneToMany(targetEntity="HashTag", mappedBy="movie", cascade={"persist"}, orphanRemoval=true)
     */
    private $hashTags;

    /**
     * persist => when the movie form is submitted, the image is persisted
     * remove => if a movie is deleted, the attached image is deleted too
     * onDelete SET NULL => if the image is removed from database, the image_id field is set to null
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $image;

    /**
     * no cascade remove annotation otherwise when a movie is deleted, the author is deleted too from database
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="movies")
     */
    private $author;

    /**
     * @var \DateTime $releaseAt
     *
     * @ORM\Column(name="released_at", type="date")
     * @Assert\NotBlank()
     */
    private $releaseAt;

    /**
     * phpname type
     * e.g. 'AuthorId'
     */
    const TYPE_PHPNAME = 'phpName';

    /**
     * column fieldname type
     * e.g. 'author_id'
     */
    const TYPE_FIELDNAME = 'fieldName';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. static::$fieldNames[static::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array(
        self::TYPE_PHPNAME => array('Id', 'Title', 'Description', 'Category', 'Actors', 'HashTags'),
        self::TYPE_FIELDNAME => array('id', 'title', 'description', 'category', 'actors', 'hashTags'),
    );


    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->hashTags = new ArrayCollection();
    }

    /**
     * Set category
     *
     * @param SingleIntIdEntity $category
     * @return Movie
     */
    public function setSingleIntIdCategory(SingleIntIdEntity $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get hashTags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHashTags()
    {
        return $this->hashTags;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return Movie
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Movie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return Movie
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add actors
     *
     * @param Actor $actors
     * @return Movie
     */
    public function addActor(Actor $actors)
    {
        $this->actors[] = $actors;

        return $this;
    }

    /**
     * Remove actors
     *
     * @param Actor $actors
     */
    public function removeActor(Actor $actors)
    {
        $this->actors->removeElement($actors);
    }

    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActors()
    {
        return $this->actors;
    }

    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }

    /**
     * Add hashTags
     *
     * @param \App\PortalBundle\Entity\HashTag $hashTag
     * @return Movie
     */
    public function addHashTag(\App\PortalBundle\Entity\HashTag $hashTag)
    {
        if (!$this->hashTags->contains($hashTag)) {
            $this->hashTags->add($hashTag);
            $hashTag->setMovie($this);
        }
    }

    /**
     * Remove hashTags
     *
     * @param \App\PortalBundle\Entity\HashTag $hashTag
     */
    public function removeHashTag(\App\PortalBundle\Entity\HashTag $hashTag)
    {
        if ($this->hashTags->contains($hashTag)) {
            $this->hashTags->removeElement($hashTag);
        }
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Movie
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     * @return Movie
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseAt()
    {
        return $this->releaseAt;
    }

    /**
     * @param \DateTime $releaseAt
     * @return Movie
     */
    public function setReleaseAt($releaseAt)
    {
        $this->releaseAt = $releaseAt;
        return $this;
    }

    /**
     * @return array
     */
    public static function getLikeFieds()
    {
        return self::$likeFieds;
    }

    /**
     * @return array
     */
    public static function getLikeFieldsSearchForm()
    {
        return self::$likeFieldsSearchForm;
    }

    /**
     * @return array
     */
    public static function getCollectionFields()
    {
        return self::$collectionFields;
    }

    /**
     * @return array
     */
    public static function getObjectFields()
    {
        return self::$objectFields;
    }

    /**
     * @return array
     */
    public static function getManagerCollectionMapping()
    {
        return self::$managerCollectionMapping;
    }

    public static function getManagerName($manager)
    {
        if (!array_key_exists($manager, static::$managerCollectionMapping)) {
            throw new \Exception('Method getManagerName() expects the parameter ' . $manager . ' to be one of ' . implode('Manager, ', array_keys(static::$managerCollectionMapping)));
        }

        return static::$managerCollectionMapping[$manager];
    }


    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = self::TYPE_PHPNAME)
    {
        $keys = static::getFieldNames($keyType);
        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTitle($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSingleIntIdCategory($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setActors($arr[$keys[4]]);
        }
        /*if (array_key_exists($keys[5], $arr)) {
            $this->setHashTags($arr[$keys[5]]);
        }*/
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws \Exception - if the type is not valid.
     */
    public static function getFieldNames($type)
    {
        if (!array_key_exists($type, static::$fieldNames)) {
            throw new \Exception('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return static::$fieldNames[$type];
    }
}
