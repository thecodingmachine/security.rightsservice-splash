<?php


namespace Mouf\Security\Controllers;

use Mouf\Html\Template\TemplateInterface;
use Mouf\Mvc\Splash\HtmlResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * A very very simple controller displaying a simple 403 page.
 * Note: this is not a 401 page (i.e.: it is not an authentication problem, it is an authorization problem)
 */
class SimpleForbiddenController implements ForbiddenController
{
    /**
     * The template to use to display.
     *
     * @var TemplateInterface
     */
    private $template;

    /**
     * The view object for the login screen.
     *
     * @var SimpleForbiddenView
     */
    private $simpleForbiddenView;

    /**
     * The content block the template will be writting into.
     *
     * @var HtmlBlock
     */
    private $contentBlock;

    /**
     * @param TemplateInterface $template
     * @param HtmlBlock $contentBlock
     * @param SimpleForbiddenView $simpleForbiddenView
     */
    public function __construct(TemplateInterface $template, HtmlBlock $contentBlock, SimpleForbiddenView $simpleForbiddenView)
    {
        $this->template = $template;
        $this->contentBlock = $contentBlock;
        $this->simpleForbiddenView = $simpleForbiddenView;
    }

    /**
     * Return a forbidden HTML 403 page.
     *
     * @return ResponseInterface
     */
    public function forbiddenPage(RequestInterface $request) : ResponseInterface
    {
        $acceptType = $request->getHeader('Accept');
        if (is_array($acceptType) && count($acceptType) > 0 && strpos($acceptType[0], 'json') !== false) {
            return new JsonResponse(['error' => ['message' => 'Access forbidden', 'type' => 'access_forbidden']], 403);
        }


        $this->contentBlock->addHtmlElement($this->simpleForbiddenView);

        return new HtmlResponse($this->template, 403);
    }
}
