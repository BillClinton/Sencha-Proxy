<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Senchaproxy {

    public function __construct()
    {
		$this->CI =& get_instance();
    }

    /**
     * Check query validity and format result array for queries
     *
	 * @param	array 	results
	 * @param	array 	callback
	 * @return	string 	JSON encoded response
     */
	function sendResponse($results, $cb = null)
	{
        // if callback was sent use it, otherwise check GET
        $callback = $cb==null ? $this->CI->input->get('callback', TRUE) : $cb;

        if ($callback)
        {
            // wrap json encoded response in callback 
            // header('Content-Type: application/x-javascript; charset=utf-8');
            $result = $callback . '(' . json_encode($results) . ');';
        } else {
            // send json encoded response
            header('Content-Type: application/x-json; charset=utf-8');
            $result = json_encode($results);
        }
        echo $result;
	}

    /**
     * Check query validity and format result array for queries
     *
	 * @param	query
	 * @return	array
     */
	function formatQueryResult($query,$total,$meta=false)
    {
        if ($query) 
        {
            $query_result = $query->result();
            $total = $total;
            $result = array (
                'success' => true,
                'total' => $total,
                'rows' => $query_result
            );
			if (is_array($meta)) $result = array_merge($result,$meta);
        } 
        else 
        {
            $result = array (
                'success' => false,
                'msg' => 'Database Error: '.$this->db->_error_message(),
                'num' => $this->db->_error_number()
            );
        }
        return $result;
    }

    /**
     * Check query validity and format result array for operations
     *
	 * @param	query
	 * @return	array
     */
	function formatOperationResult($query, $record = array())
    {
        //$this->chromephp->log($extra_params);
        if ($query) 
        {
            // query successful
            $result = array(
                'success'   => true,
                'msg'       => 'Operation successful'
            );

            if (count($record)>0) 
            {
				$rows = array(
					'rows'	=> $record
				);
                $result = array_merge($result,$rows);
            }
        } 
        else 
        {
            // database error 
            $result = array (
                'success' => false,
                'msg' => 'Database Error: '.$this->db->_error_message(),
                'num' => $this->db->_error_number()
            );
        }
        return $result;
    }
}

/* End of file SenchaProxy.php */
