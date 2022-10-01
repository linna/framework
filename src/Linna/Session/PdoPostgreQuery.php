<?php

declare(strict_types=1);

/**
 * This file is part of the Linna Framwork.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@tim.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Session;

/**
 * Postgre queries to store sessions in database.
 */
class PdoPostgreQuery extends PdoAbstractQuery
{
    final public const QUERY_READ = 'SELECT session_data FROM public.session WHERE session_id = :id';
    final public const QUERY_WRITE = 'INSERT INTO public.session(session_id, session_data) VALUES (:id, :data) ON CONFLICT (session_id) DO UPDATE SET session_data = :data, last_update = now()';
    final public const QUERY_DESTROY = 'DELETE FROM public.session WHERE session_id = :id';
    final public const QUERY_GC = 'DELETE FROM public.session WHERE last_update < :max_lifetime';
}
