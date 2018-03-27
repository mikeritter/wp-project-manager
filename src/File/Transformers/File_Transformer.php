<?php

namespace WeDevs\PM\File\Transformers;

use WeDevs\PM\File\Models\File;
use League\Fractal\TransformerAbstract;
use WeDevs\PM\Core\File_System\File_System;
use WeDevs\PM\User\Transformers\User_Transformer;
use WeDevs\PM\Common\Traits\Resource_Editors;

class File_Transformer extends TransformerAbstract {

    use Resource_Editors;

    protected $defaultIncludes = [
        'creator', 'updater'
    ];

    public function transform( File $item ) {
        $file = File_System::get_file( $item->attachment_id );
        $file = is_array( $file ) ? $file : [];
        
        $model_data = [
            'id'            => (int) $item->id,
            'fileable_id'   => $item->fileable_id,
            'fileable_type' => $item->fileable_type,
            'directory'     => $item->directory,
            'attachment_id' => $item->attachment_id,
            'attached_at'   => format_date( $item->created_at ),
            'fileable'      => $this->get_fileabel($item)
        ];

        return array_merge( $model_data, $file );
    }

    public function get_fileabel( $item ) {

        if ( $item->fileable_type == 'comment') {
            return $item->comments()->get()->toArray();
        }
    }
}