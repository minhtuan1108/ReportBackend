<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static COMPLETE()
 * @method static static SENT()
 * @method static static DRAFT()
 * @method static static PROCESS()
 * @method static static IGNORE()
 */

final class ReportStatus extends Enum
{
    const COMPLETE = 'complete'; // đã hoàn thành
    const SENT = 'sent'; // đã gửi
    const DRAFT = 'draft'; // nháp (trạng thái này chỉ có ở client, sẽ không sử dụng ở server) -> ghi cho vui
    const PROCESS = 'process'; // đang thực hiện
    const IGNORE = 'ignore'; // bị bỏ qua
}
