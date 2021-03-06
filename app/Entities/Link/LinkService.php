<?php

namespace App\Entities\Link;

use App\Entities\Link\Exceptions\LinkNotFoundException;
use Illuminate\Http\UploadedFile;

class LinkService
{
    /**
     * @var LinkStorage
     */
    private $linkStorage;
    /**
     * @var LinksFileStorage
     */
    private $linksFileStorage;

    /**
     * @param LinkStorage $linkStorage
     * @param LinksFileStorage $linksFileStorage
     */
    public function __construct(LinkStorage $linkStorage, LinksFileStorage $linksFileStorage)
    {
        $this->linkStorage = $linkStorage;
        $this->linksFileStorage = $linksFileStorage;
    }

    /**
     * @return LinkEntity[]
     */
    public function getLinks(int $user_id): array
    {
        $links = $this->linkStorage->getLinks($user_id);
        foreach ($links as $link){
            $link->setHasPicture($this->linksFileStorage->pictureExists($link));
        }
        return  $links;
    }

    /**
     * @return LinkEntity[][]
     */
    public function getLinksWithPaginator(int $user_id): array
    {
        $links = $this->linkStorage->getLinksWithPaginator(
            $user_id, $this->getSortField(), $this->getSortOrder()
        );
        foreach ($links['links'] as $link){
            $link->setHasPicture($this->linksFileStorage->pictureExists($link));
        }
        return  $links;
    }

    /**
     * @param LinkEntity $link
     */
    public function storeLink(LinkEntity $link)
    {
        $this->linkStorage->store($link);
    }

    /**
     * @param int $user_id
     * @param int $link_id
     * @return LinkEntity
     * @throws LinkNotFoundException
     */
    public function getLink(int $link_id, int $user_id): LinkEntity
    {
        return $this->linkStorage->getLink($link_id, $user_id);
    }

    /**
     * @param string $hash
     * @return LinkEntity
     * @throws LinkNotFoundException
     */
    public function getLinkByHash(string $hash): LinkEntity
    {
        return $this->linkStorage->getLinkByHash($hash);
    }

    /**
     * @param LinkEntity $link
     */
    public function updateLink(LinkEntity $link)
    {
        $this->linkStorage->update($link);
    }

    /**
     * @param LinkEntity $link
     * @param UploadedFile $file
     */
    public function uploadLinkFile(LinkEntity $link, UploadedFile $file)
    {
        $this->linksFileStorage->storeFile($link, $file);
    }

    /**
     * @param int $link_id
     * @param int $user_id
     * @throws LinkNotFoundException
     */
    public function delete(int $link_id, int $user_id)
    {
        $link = $this->getLink($link_id, $user_id);
        $this->linksFileStorage->deletePicture($link);
        $this->linkStorage->delete($link);

    }

    /**
     * @param string $sort
     * @return void
     */
    public function setSorting(string $sort)
    {
        if (empty($sort)){
            return;
        }
        $session = app('session');
        $session->put("sort_field", $sort);
        $order = $session->get("sort_order");
        if ($order == 'desc'){
            $session->put("sort_order", "asc");
            return;
        }
        $session->put("sort_order", "desc");
    }

    private function getSortField(): string
    {
         return app("session")->get("sort_field") ?? "id";
    }

    private function getSortOrder(): string
    {
        return app("session")->get("sort_order") ?? "desc";
    }
}
