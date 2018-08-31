<?php
declare(strict_types=1);
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @return array
 */
function no_registration_words(): array
{
    return [
        'No match for',
        'NOT FOUND',
        'No Data Found',
        'has not been registered',
        'does not exist',
        'No match!!',
        'available for registration',
    ];
}

/**
 * @return array
 */
function reserved_words(): array
{
    return [
        'reserved name',
        'Reserved Domain',
        'registry reserved',
        'has been reserved',
    ];
}

/**
 * @return array
 */
function get_cctld_list(): array
{
    return [
        'jp',
        'co.jp',
        'or.jp',
        'ne.jp',
        'ac.jp',
        'ed.jp',
        'go.jp',
        'hokkaido.jp',
        'miyagi.jp',
        'fukushima.jp',
        'aomori.jp',
        'iwate.jp',
        'yamagata.jp',
        'akita.jp',
        'tokyo.jp',
        'kanagawa.jp',
        'saitama.jp',
        'chiba.jp',
        'ibaraki.jp',
        'gunma.jp',
        'tochigi.jp',
        'niigata.jp',
        'nagano.jp',
        'yamanashi.jp',
        'aichi.jp',
        'shizuoka.jp',
        'gifu.jp',
        'ishikawa.jp',
        'toyama.jp',
        'fukui.jp',
        'osaka.jp',
        'hyogo.jp',
        'kyoto.jp',
        'nara.jp',
        'mie.jp',
        'shiga.jp',
        'wakayama.jp',
        'hiroshima.jp',
        'okayama.jp',
        'yamaguchi.jp',
        'shimane.jp',
        'tottori.jp',
        'ehime.jp',
        'kagawa.jp',
        'tokushima.jp',
        'kochi.jp',
        'fukuoka.jp',
        'kumamoto.jp',
        'kagoshima.jp',
        'nagasaki.jp',
        'oita.jp',
        'miyazaki.jp',
        'saga.jp',
        'okinawa.jp',
        'ac',
        'am',
        'be',
        'bz',
        'cm',
        'cn',
        'cz',
        'gs',
        'mu',
        'mx',
        'co.uk',
        'me.uk',
        'org.uk',
        'vg',
        'vn',
        'id',
        'tw',
    ];
}
