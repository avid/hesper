<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Evgeny V. Kokovikhin
 */
namespace Hesper\Main\Monitoring;

use Hesper\Core\DB\PgSQL;

/**
 * @ingroup DB
 **/
final class PinbedPgSQL extends PgSQL {

	public function connect() {
		if (PinbaClient::isEnabled()) {
			PinbaClient::me()->timerStart('pg_sql_connect_' . $this->basename, ['group' => 'sql', 'pg_sql_connect' => $this->basename]);
		}

		$result = parent::connect();

		if (PinbaClient::isEnabled()) {
			PinbaClient::me()->timerStop('pg_sql_connect_' . $this->basename);
		}

		return $result;
	}

	public function queryRaw($queryString) {
		if (PinbaClient::isEnabled()) {
			$queryLabel = substr($queryString, 0, 5);

			PinbaClient::me()->timerStart('pg_sql_query_' . $this->basename, ['group' => 'sql', 'pg_sql_query' => $queryLabel, 'pg_sql_server' => $this->hostname, 'pg_sql_base' => $this->basename]);
		}

		try {
			$result = parent::queryRaw($queryString);

			if (PinbaClient::isEnabled()) {
				PinbaClient::me()->timerStop('pg_sql_query_' . $this->basename);
			}

			return $result;

		} catch (\Exception $e) {
			if (PinbaClient::isEnabled()) {
				PinbaClient::me()->timerStop('pg_sql_query_' . $this->basename);
			}

			throw $e;
		}
	}
}
