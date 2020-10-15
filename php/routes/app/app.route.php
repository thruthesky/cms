<?php


class AppRoute extends ApiLibrary {

	public function version() {
		$this->response( [ 'version' => APP_VERSION, 'request' => $_REQUEST ] );
	}

	/**
	 * Return settings client app
	 * @see
	 */
	public function settings() {

		$forums = [];
		if ( in( 'forums' ) ) {
			foreach ( in( 'forums' ) as $slug ) {
				$cat = get_category_by_slug( $slug );
				$forums[] = get_forum_settings( $cat );
			}
		} else {
			$cats = get_categories();
			foreach( $cats as $cat ) {
				$forums[] = get_forum_settings($cat);
			}
		}

		$lang = in('lang') ? in('lang') : 'en';
		$i18n = [];
		foreach( get_i18n(Config::$i18n_languages) as $k => $values ) {
			$i18n[$k] = $values[$lang];
		}
		$data = [
			'version'          => APP_VERSION,
			'kakaoLoginApiURL' => Config::$kakaoLoginApiURL,
			'naverLoginApiURL' => Config::$naverLoginApiURL,
			'i18n'             => $i18n,
			'forums'           => $forums,
		];
		$this->response( $data );
	}

	public function mobileCountryCodes() {

		$txt   = file_get_contents( THEME_PATH . '/etc/country.code.json' );
		$json  = json_decode( $txt, true );
		$codes = [];
		foreach ( $json as $c ) {
			if ( strpos( $c['name'], 'Korea, Democratic' ) !== false ) {
				continue;
			}
			if ( strpos( $c['name'], 'Korea, Republic' ) !== false ) {
				$c['name'] = 'Korea';
			}
			if ( strlen( $c['name'] ) > 30 ) {
				$c['name'] = substr( $c['name'], 0, 30 ) . '...';
			}
			$codes[ $c['Iso'] ] = $c['name'] . ' (' . $c['Iso'] . ')';
		}

		$this->response( [ 'codes' => $codes ] );
	}
}
