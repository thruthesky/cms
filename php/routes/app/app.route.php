<?php


class AppRoute extends ApiLibrary {

	public function version() {
		$this->response( [ 'version' => APP_VERSION, 'request' => $_REQUEST ] );
	}

	public function settings() {
		$data = [
			'version'          => APP_VERSION,
			'kakaoLoginApiURL' => Config::$kakaoLoginApiURL,
			'naverLoginApiURL' => Config::$naverLoginApiURL,
			'i18n'             => [],
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

		$this->response( ['codes' => $codes] );
	}
}
