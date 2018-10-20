<?php
/**
 * Created by PhpStorm.
 * User: Hamlet
 * Date: 27.09.2018
 * Time: 20:26
 */

namespace PanteraFox\Subscription\Data\Entity;


class UserNews
{
    const SUBSCRIPTION_TYPE = 'subscription';

    const NEW_VIDEO_TYPE = 'new_video';

    const STATUS_NEW = 'new';

    const STATUS_READ = 'read';

    /** @var string */
    private $userId;

    /** @var string */
    private $content;

    /** @var string */
    private $type;

    /** @var string */
    private $status = self::STATUS_NEW;

    /** @var string */
    private $createdAt;

    /** @var string */
    private $updatedAt;

    public function __construct ()
    {
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     *
     * @return UserNews
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return UserNews
     */
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return UserNews
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return UserNews
     */
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @return UserNews
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     *
     * @return UserNews
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}