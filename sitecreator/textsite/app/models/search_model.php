<?php
class SearchModel extends AppComponent {
	use SearchPagination;

	private $totalRecords;
	private $totalRecordsFound;
	private $totalRecordsAvailable;
	private $limit;

	private $keyword;
	private $currentPage;
	private $products;

	function searchProducts( $page ) {
		$this->keyword =  urlencode( $_GET['q'] );
		$this->currentPage = $page;

		$prosp = new ProsperentAPI();
		$results = $prosp->curlHit( $this->keyword, $this->currentPage );

		if ( $results ) {
			$this->products = $results['data'];
			$this->totalRecords = $results['totalRecords'];
			$this->totalRecordsFound = $results['totalRecordsFound'];
			$this->totalRecordsAvailable = $results['totalRecordsAvailable'];
			$this->limit = $results['limit'];
			return true;
		} else {
			return false;
		}
		
	}

	function getProducts() {
		return $this->addSID();
	}

	function addSID() {
		foreach ( $this->products as $product ) {
			$key = $product['keyword'];
			$result[$key]['affiliate_url'] = $product['affiliate_url'] . '&sid=search_' . SID . '_' . $this->keyword;
			$result[$key]['image_url'] = $product['image_url'];
			$result[$key]['description'] = $product['description'];
			$result[$key]['category'] = $product['category'];
			$result[$key]['price'] = $product['price'];
			$result[$key]['merchant'] = $product['merchant'];
			$result[$key]['brand'] = $product['brand'];
			$result[$key]['category'] = $product['category'];
		}
		return $result;
	}

	function pagination() {
		return $this->setPagination();
	}
}

trait SearchPagination {
	function setPagination() {
		$totalPage = $this->calculateTotalPage();
		return $this->createPageLinks( $totalPage );
	}

	function createPageLinks( $totalPage ) {
		for ( $i = 1; $i <= $totalPage; $i++ ) {
			if ( $i == $this->currentPage ) 
				$status = false;
			else
				$status = true;

			$pagination[$i]['link'] = $link = HOME_URL . 'search/' . $i . '/?q=' . $this->keyword;
			$pagination[$i]['status'] = $status;
		}
		return $pagination;
	}

	function calculateTotalPage() {
		return ceil( $this->totalRecordsAvailable / $this->limit );
	}
}

class ProsperentAPI {
	function curlHit( $keyword, $page ) {
		$url = 'http://api.prosperent.com/api/search?api_key=' . PROSPERENT_API_KEY . '&query=';
		$url = $url . $keyword . '&page=' . $page . '&limit=50';

		$curl = curl_init();
		curl_setopt_array($curl, array( // Set options
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_CONNECTTIMEOUT => 30,
		    CURLOPT_TIMEOUT => 30
		));

		$response = curl_exec($curl); // Send the request
		curl_close($curl);  // Close request
		$response = json_decode($response, true);  // Convert the json response to an array

		if (count($response['errors'])) { // Check for errors
		    //throw new Exception(implode('; ', $response['errors']));
		    return false;
		}

		if ( $response['totalRecords'] <= 0 ) {
			return false;
		}

		return $response;	
	}
}

