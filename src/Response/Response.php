<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Entities\Message;
use ShoZaSong\Bot\Telegram\ResponseFactory;
use ShoZaSong\Bot\Telegram\ResponseFactoryInterface;

abstract class Response implements ResponseInterface
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Response constructor.
     * @param Message $message
     * @param ResponseFactoryInterface|null $responseFactory
     */
    public function __construct(Message $message, ResponseFactoryInterface $responseFactory = null)
    {
        $this->message = $message;
        $this->responseFactory = null !== $responseFactory ? $responseFactory : new ResponseFactory;
    }

    /**
     * @return Message
     */
    protected function getMessage()
    {
        return $this->message;
    }

    /**
     * @return ResponseFactoryInterface
     */
    protected function getResponseFactory()
    {
        return $this->responseFactory;
    }
}