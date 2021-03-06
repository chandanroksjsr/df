<?php

namespace App\Entity\Repository;


use App\Entity\AdgroupPreview;
use App\Entity\AdwordsAd;
use App\Entity\Repository\Contract\iAdgroupPreview;


class AdgroupPreviewRepository implements iAdgroupPreview
{

    private $adgroup_preview;

    /**
     * AdwordsAdRepository constructor.
     * @param AdwordsAd $adwords_ad
     */
    public function __construct(AdgroupPreview $adgroup_preview)
    {
        $this->adgroup_preview = $adgroup_preview;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function removeSingleAdgroup($id)
    {
        return $this->adgroup_preview->where('id',$id)->delete();
    }

    public function getAdgroupPreviewByName($adgroup_name,$campaign_id)
    {
        return $this->adgroup_preview->where('fk_campaigns_preview_id',$campaign_id)
            ->where('adgroup_name',$adgroup_name)
            ->first();
    }

    /**
     * @param $campaign_id
     * @return mixed
     */
    public function getAdgroupFromCampaign($campaign_id)
    {
        return $this->adgroup_preview->where('fk_campaigns_preview_id',$campaign_id)->get();
    }


    /**
     * @param $data
     */
    public function createAdgroupPreview($data,$id=0)
    {
        if($id == 0 ) {
            return $this->adgroup_preview->create($data);
        } else {
            return $this->adgroup_preview->where('id',$id)->update($data);
        }

    }


    /**
     * @param $fk_adwords_feed_id
     * @return array
     */
    public function getAdgroupData($fk_adwords_feed_id)
    {
        $returnArray = [];
         foreach($this->adgroup_preview->where('fk_adwords_feed_id',$fk_adwords_feed_id)->get() as $values) {
             $returnArray[$values->fk_campaigns_preview_id][$values->adgroup_name] = $values->id;
         }
         return $returnArray;

    }


    /**
     * @param $fk_campaigns_preview_id
     * @param bool $to_array
     * @return mixed
     */
    public function getAdgroupDataFromCampaigns($fk_campaigns_preview_id,$to_array = true)
    {
        if(!$to_array) {
            return $this->adgroup_preview->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)->get();
        } else {
            return $this->adgroup_preview->where('fk_campaigns_preview_id',$fk_campaigns_preview_id)->pluck('adgroup_name','id')->toArray();
        }

    }


    /**
     * @param $fk_adwords_feed_id
     * @return mixed
     */
    public function getAdgroupsToDelete($fk_adwords_feed_id)
    {
        return $this->adgroup_preview->where('fk_adwords_feed_id',$fk_adwords_feed_id)->where('delete_from_adwords',true)->get();
    }


}