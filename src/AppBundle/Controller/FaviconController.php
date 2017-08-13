<?php
declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Responds with manifest files and other files used to render the icons
 * properly
 *
 * @author Roelof Roos
 */
class FaviconController extends Controller
{
    /**
     * Returns the manifest file, which contains information for Evergreen browsers
     *
     * @return Response
     */
    public function manifestAction() : Response
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P14D'));

        $response = $this
            ->render('favicon/manifest.json.twig')
            ->setDate(new \DateTime)
            ->setPublic()
            ->setExpires($expire);

        $response->headers->add(['Content-Type' => 'text/json; charset=utf-8']);
        return $response;
    }

    /**
     * Returns the browserconfig file, which contains information for Edge and
     * Internet Exploder.
     *
     * @return Response
     */
    public function browserConfigAction() : Response
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P14D'));

        $response = $this
            ->render('favicon/browserconfig.xml.twig')
            ->setDate(new \DateTime)
            ->setPublic()
            ->setExpires($expire);

        $response->headers->add(['Content-Type' => 'text/xml; charset=utf-8']);
        return $response;
    }
}
