<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\SiteSetting;

class SiteSettingController extends Controller
{
    public function siteSettings()
    {
        $data = SiteSetting::select('name', 'email', 'phone', 'header_logo', 'footer_logo', 'fav_icon', 'dark_fav_icon', 'facebook_url', 'twitter_url', 'youtube_url', 'whatsapp_url', 'site_address', 'welcome_popup_image', 'section_order')
            ->first();
        $data['status'] = true;
        $data['header_logo'] = str_contains($data['header_logo'], 'http') ? $data['header_logo'] : asset('storage/' . $data['header_logo']);
        $data['footer_logo'] = str_contains($data['footer_logo'], 'http') ? $data['footer_logo'] : asset('storage/' . $data['footer_logo']);
        $data['fav_icon'] = str_contains($data['fav_icon'], 'http') ? $data['fav_icon'] : asset('storage/' . $data['fav_icon']);
        $data['dark_fav_icon'] = str_contains($data['dark_fav_icon'], 'http') ? $data['dark_fav_icon'] : asset('storage/' . $data['dark_fav_icon']);
        $data['welcome_popup_image'] = str_contains($data['welcome_popup_image'], 'http') ? $data['welcome_popup_image'] : asset('storage/' . $data['welcome_popup_image']);
        $data['section_order'] = !empty($data["section_order"]) ? array_values(json_decode($data["section_order"], true)) : [];
        return $data;
    }
}
