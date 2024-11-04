<?php

namespace App\Enums;

enum PostStatusEnum: string{
    case DRAFT = 'draft';

    case PENDING = 'pending';

    case REJECTED = 'rejected';

    case APPROVED = 'approved';

    case ARCHIVED = 'archived';

    case PUBLISHED = 'published';

}