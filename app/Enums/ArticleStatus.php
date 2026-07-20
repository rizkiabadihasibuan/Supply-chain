<?php
namespace App\Enums;
/** ArticleStatus – Status publikasi artikel */
enum ArticleStatus: string {
    case Published = 'Published';
    case Draft     = 'Draft';
    case Archived  = 'Archived';
}
