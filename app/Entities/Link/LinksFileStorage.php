<?php

namespace App\Entities\Link;



use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LinksFileStorage
{

    public const ALLOWED_EXTENSIONS = ['jpeg', 'jpg', 'png', 'gif'];
    public const MAX_FILE_SIZE = 1000000;
    /**
     * @var `Filesystem
     */
    private $disk;

    public function __construct()
    {
        $this->disk = app('filesystem')->disk('links');
    }

    /**
     * @param int $user_id
     */
    public function deleteUserFolder(int $user_id)
    {
        $this->disk->delete("/".$user_id);
    }

    /**
     * @param LinkEntity $linkEntity
     * @return bool
     */
    public function pictureExists(LinkEntity $linkEntity): bool
    {
        return $this->disk->exists( $this->picturePath($linkEntity));
    }

    /**
     * @param LinkEntity $linkEntity
     * @param UploadedFile $file
     */
    public function storeFile(LinkEntity $linkEntity, UploadedFile $file)
    {
        if ($this->pictureExists($linkEntity)){
            $this->deletePicture($linkEntity);
        }
        $this->disk->putFileAs("/".$linkEntity->getUserId(), $file, $linkEntity->getHash().".".$linkEntity->getExtension());
    }

    /**
     * @param LinkEntity $linkEntity
     */
    public function deletePicture(LinkEntity $linkEntity){
        $this->disk->delete($this->picturePath($linkEntity));
    }

    /**
     * @param string $file
     * @param int $user_id
     * @return StreamedResponse
     */
    public function image(string $file, int $user_id): StreamedResponse
    {
        return $this->disk->download("/".$user_id."/".$file);
    }

    /**
     * @param LinkEntity $linkEntity
     * @return string
     */
    private function picturePath(LinkEntity $linkEntity): string
    {
        return "/".$linkEntity->getUserId()."/".$linkEntity->getHash().".".$linkEntity->getExtension();
    }
}
