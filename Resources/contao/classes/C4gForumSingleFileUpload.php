<?php
/*
 * This file is part of con4gis,
 * the gis-kit for Contao CMS.
 *
 * @package    con4gis
 * @version    7
 * @author     con4gis contributors (see "authors.txt")
 * @license    LGPL-3.0-or-later
 * @copyright  Küstenschmiede GmbH Software & Design
 * @link       https://www.con4gis.org
 */

namespace con4gis\ForumBundle\Resources\contao\classes;

use Contao\FileUpload;

/**
 * Single file upload handler to return only 1 input field for a single file upload.
 *
 * Class C4gForumSingleFileUpload
 */
class C4gForumSingleFileUpload extends FileUpload
{
    /**
     * Overwrite parents method to only output 1 input field with no multiple attribute.
     *
     * @return string
     */
    public function generateMarkup()
    {
        $sField = '<input type="file" name="' . $this->strName . '[]" class="tl_upload_field"><br>';

        return '<div id="upload-fields">' . $sField . '</div>';
    }
}
