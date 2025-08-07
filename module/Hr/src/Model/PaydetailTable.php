<?php
namespace Hr\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Expression;

class PaydetailTable extends AbstractTableGateway
{
	protected $table = 'hr_pay_details'; //tablename

	public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }	

	/**
	 * Return All records of table
	 * @return Array
	 */
	public function getAll()
	{  
	    $adapter = $this->adapter;
	    $sql = new Sql($adapter);
	    $select = $sql->select();
	    $select->from(array('pd' => $this->table))
				->join(array('ph'=>'hr_pay_heads'),'pd.pay_head=ph.id',array('pay_head','code', 'pay_head_id'=>'id', 'deduction'))
			   ->join(array('pht'=>'hr_pay_head_type'),'ph.payhead_type=pht.id',array('head_type', 'code', 'payhead_type_id'=>'id', 'deduction'));
	    
	    $selectString = $sql->getSqlStringForSqlObject($select);
	    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	    return $results;
	}
	
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function get($param)
	{
		$where = ( is_array($param) )? $param: array('pd.id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from(array('pd' => $this->table))
			   ->join(array('ph'=>'hr_pay_heads'),'pd.pay_head=ph.id',array('pay_head', 'code', 'pay_head_id'=>'id'))
			   ->join(array('pht'=>'hr_pay_head_type'),'ph.payhead_type=pht.id',array('head_type', 'code', 'payhead_type_id'=>'id', 'deduction'))
		       ->where($where);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	/**
	 * Return records of given condition array | given id
	 * @param Int $id
	 * @return Array
	 */
	public function getAllow($param,$column)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
	    $select->from( $this->table)
		->columns(array('amount' => new Expression('SUM('.$column.')'))) 
		       ->where($where);
			   $select->where->in('pay_head',[3,4,7,8,29]);
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString;exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return column value of given id
	 * @param Int $id
	 * @param String $column
	 * @return String | Int
	 */
	public function getColumn($param, $column)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$fetch = array($column);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns($fetch);
		$select->where($where);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		$columns="";
		foreach ($results as $result):
		$columns =  $result[$column];
		endforeach;
		 
		return $columns;
	}
	
	/**
	 * Save record
	 * @param String $array
	 * @return Int
	 */
	public function save($data)
	{
	   if ( !is_array($data) ) $data = $data->toArray();
	    $id = isset($data['id']) ? (int)$data['id'] : 0;	    
	    if ( $id > 0 )
	    {
	    	$result = ($this->update($data, array('id'=>$id)))?$id:0;
	    } else {
	        $this->insert($data);
	    	$result = $this->getLastInsertValue(); 
	    }	    	    
	    return $result;	       
	}

	/**
     *  Delete a record
     *  @param int $id
     *  @return true | false
     */
	public function remove($id)
	{
		return $this->delete(array('id' => $id));
	}
	
	/**
	* check particular row is present in the table 
	* with given column and its value
	* 
	*/
	public function isPresent($column, $value)
	{
		$column = $column; $value = $value;
		$resultSet = $this->select(function(Select $select) use ($column, $value){
			$select->where(array($column => $value));
		});
		
		$resultSet = $resultSet->toArray();
		return (sizeof($resultSet)>0)? TRUE:FALSE;
	} 

	/**
	 * Return Min value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMin($where = NULL, $column)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'min' => new Expression('MIN('.$column.')')
		));
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['min'];
		endforeach;
	
		return $column;
	}
	
	/**
	 * Return max value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Int
	 */
	public function getMax($where=NULL, $column)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'max' => new Expression('MAX('.$column.')')
		));
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
		$column =  $result['max'];
		endforeach;
	
		return $column;
	}
	
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMaxRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$adapter = $this->adapter;
		
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MAX('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return records of given condition array
	 * @param Int $column
	 * @param Int $param
	 * @return Array
	 */
	public function getMinRow($column,$param)
	{
		$where = ( is_array($param) )? $param: array('id' => $param);
		$adapter = $this->adapter;
	
		$sub0 = new Select($this->table);
		$sub0->columns(array(
				$column => new Expression('MIN('.$column.')')
		));
		//$sub0 = $sub0->toArray();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table)
				->where($where)
				->where($column);
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
		return $results;
	}
	
	/**
	 * Return payhead amount for payroll summary
	 * @param Array $param
	 * @return Array
	 */
	public function getAmtforSummary($param)
	{
		extract($param);	
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pd' => $this->table));
                $select->columns(array('amount' => new Expression('SUM(pd.actual_amount)')))
			->join(array('pr' => 'hr_payroll'), 'pr.id = pd.pay_roll', array())
			->join(array('his'=>'hr_emp_history'), 'his.id=pr.emp_his', array())
			->join(array('ph' => 'hr_pay_heads'), 'ph.id = pd.pay_head', array())
			->join(array('l'=>'adm_location'), 'his.location = l.id', array())
			->join(array('r'=>'adm_region'),'r.id = l.region', array())
			->join(array('e' => 'hr_employee'), 'e.id = pr.employee', array());
		$select->where(array('pr.year' => $year, 'pr.month' => $month, 'ph.payhead_type' => $subhead));
		if($activity != -1):
			$select->where(array('his.activity' => $activity));
		endif;
	
		if($department != -1):
			if($location != -1):
				$select->where(array('his.department'=>$department, 'his.location'=>$location));
			else:
				$select->where(array('his.department' => $department));
				if($region != -1):
					$select->where(array('l.region' => $region));
				endif;
			endif;
		else:
			if($location != -1):
				$select->where(array('his.location'=>$location));
			else:
				if($region != -1):
					$select->where(array('l.region'=>$region));
				endif;
			endif;
		endif;
	
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach($results as $result);
		return $result['amount'];
	}
	/**
	 * CONTROL SUMMARY
	 * Return payhead amount for payroll summary
	 * @param Array $param
	 * @return Array
	 */
	public function getControlSummaryAmt($param,$status)
	{
		$param = (is_array($param))? $param: array($param);
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pd' => $this->table));
		$select->columns(array('amount' => new Expression('SUM(pd.actual_amount)')))
			->join(array('pr' => 'hr_payroll'), 'pr.id = pd.pay_roll', array())
			->join(array('his'=>'hr_emp_history'), 'his.id=pr.emp_his', array())
		    ->where($param);
		if($status == 'R'):
			$select->where->NotequalTo('his.position_level','19');
		else:
			$select->where->equalTo('his.position_level','19');
		endif;
		$selectString = $sql->getSqlStringForSqlObject($select);
		//echo $selectString; exit;
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach($results as $result);
		return $result['amount'];
	}
	/**
	 * Return sum value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Decimal
	 */
	public function getSum($column,$where=NULL)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->columns(array(
				'sum' => new Expression('SUM('.$column.')')
		));
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
			$column =  $result['sum'];
		endforeach;

		return number_format((float)$column, 2, '.', ',');
	}
	
	/**
	* Return Distinct value of the column
	* @param Array $where
	* @param String $column
	* @return Array | Int
	*/
	public function getDistinct($column, $where = NULL)
	{
	$adapter = $this->adapter;
	$sql = new Sql($adapter);
	$select = $sql->select();
	$select->from(array('pd' => $this->table))
        ->join(array('ph' => 'hr_pay_heads'), 'ph.id = pd.pay_head', array())
        ->join(array('pht' => 'hr_pay_head_type'), 'pht.id = ph.payhead_type', array('deduction', 'head_type'));
	$select->columns(array(
	'distinct' => new Expression('DISTINCT(' . $column . ')')
	));
	//$select->order(array($column.' ASC'));
	if($where!=NULL){
	$select->where($where)
	->order(array($column.' ASC'));
	}
	$selectString = $sql->getSqlStringForSqlObject($select);
	$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

	$column = array();
	foreach ($results as $result):
	array_push($column,$result['distinct']);
	endforeach;

	return $column;
	}
	/**
	 * Return sum value of the column
	 * @param Array $where
	 * @param String $column
	 * @return String | Decimal
	 */
	public function getSumGross($column, $where = NULL)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('pd'=>$this->table));
		$select->columns(array(
				'sum' => new Expression('SUM('.$column.')')
		));
		$select->join(array('pr'=>'hr_payroll'), 'pr.id=pd.pay_roll', array())
		->join(array('em'=>'hr_employee'), 'pr.employee=em.id', array('location'));
				//->where($param);
		if($where!=NULL){
			$select->where($where);
		}
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
	
		foreach ($results as $result):
			$column =  $result['sum'];
		endforeach;

		return number_format((float)$column, 2, '.', ',');
	}
	/**Get All Reports
	public function getDistinctReport($column, $data1,$where = NULL)
    {
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('pd'=>$this->table))
			
			   ->join(array('ph'=>'hr_pay_heads'), 'ph.id = pd.pay_head', array())
			   ->join(array('pht'=>'hr_pay_head_type'), 'pht.id = ph.payhead_type', array('deduction', 'head_type'))
			   ->join(array('pr'=>'hr_payroll'), 'pr.id = pd.pay_roll', array())
			   ->join(array('em'=>'hr_employee'), 'em.id = pr.employee', array('location'));
          $select->columns(array(
            'location' => new Expression('DISTINCT('.$column.')'),
			//'location' => new Expression('DISTINCT('.$column.')'),
           // 'location' => new Expression('GROUP_CONCAT(DISTINCT(location))'),
            'amount' => new Expression('SUM((amount))'),
         ))->group('em.location');
		 if($where!=NULL){
			$select->where($where);
		}
         $select->where(array('pr.year'=>$data1['year']));
		  $select->where(array('pr.month'=>$data1['month']));
           
        //$select->where(array('b.status' =>8));

	echo 	$selectString = $sql->getSqlStringForSqlObject($select);exit;
        $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
    
        return $results;
    }*/
	public function getDistinctReport($column, $data1, $where = NULL)
{
    $adapter = $this->adapter;
    $sql = new Sql($adapter);
    $select = $sql->select();
    $select->from(array('pd' => $this->table))
        ->join(array('ph' => 'hr_pay_heads'), 'ph.id = pd.pay_head', array())
        ->join(array('pht' => 'hr_pay_head_type'), 'pht.id = ph.payhead_type', array('deduction', 'id'))
        ->join(array('pr' => 'hr_payroll'), 'pr.id = pd.pay_roll', array())
        ->join(array('em' => 'hr_employee'), 'em.id = pr.employee', array('location'));

    $select->columns(array(
        'location' => new Expression('DISTINCT('.$column.')'),
        'amount' => new Expression('SUM(pd.amount)'),
    ))->group(array('em.location', 'pht.head_type'));

    if ($where != NULL) {
        $select->where($where);
    }

    $select->where(array('pr.year' => $data1['year']));
    $select->where(array('pr.month' => $data1['month']));

    $selectString = $sql->getSqlStringForSqlObject($select);
    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

    return $results;
}
/*Get list of house rent deduction location wise*/
public function getEmployeeDed($column, $data1, $where = NULL)
{
    $adapter = $this->adapter;
    $sql = new Sql($adapter);
    $select = $sql->select();
    $select->from(array('pd' => $this->table))
        ->join(array('ph' => 'hr_pay_heads'), 'ph.id = pd.pay_head', array())
        ->join(array('pht' => 'hr_pay_head_type'), 'pht.id = ph.payhead_type', array('deduction', 'id'))
        ->join(array('pr' => 'hr_payroll'), 'pr.id = pd.pay_roll', array())
        ->join(array('em' => 'hr_employee'), 'em.id = pr.employee', array('location'));

    $select->columns(array(
        'location' => new Expression('DISTINCT('.$column.')'),
        'amount' => new Expression('SUM(pd.amount)'),
    ))->group(array('em.location', 'pht.head_type'));

    if ($where != NULL) {
        $select->where($where);
    }

    $select->where(array('pr.year' => $data1['year']));
    $select->where(array('pr.month' => $data1['month']));

     $selectString = $sql->getSqlStringForSqlObject($select);
	 //echo  $selectString;exit;
    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

    return $results;
}
/*Get list of payhead deduction location wise except house rent*/
public function getDistinctDeduction($column, $data1, $where = NULL)
{
    $adapter = $this->adapter;
    $sql = new Sql($adapter);
    $select = $sql->select();
    $select->from(array('pd' => $this->table))
        ->join(array('ph' => 'hr_pay_heads'), 'ph.id = pd.pay_head', array())
        ->join(array('pht' => 'hr_pay_head_type'), 'pht.id = ph.payhead_type', array('deduction', 'id'))
        ->join(array('pr' => 'hr_payroll'), 'pr.id = pd.pay_roll', array());
       // ->join(array('em' => 'hr_employee'), 'em.id = pr.employee', array('location'));

    $select->columns(array(
        'id' => new Expression('DISTINCT('.$column.')'),
        'amount' => new Expression('SUM(pd.amount)'),
    ))->group(array('pht.head_type'));

    if ($where != NULL) {
        $select->where($where);
    }

    $select->where(array('pr.year' => $data1['year']));
    $select->where(array('pr.month' => $data1['month']));
	$select->where->notIn('pht.id', [30]);
     $selectString = $sql->getSqlStringForSqlObject($select);
	 //echo $selectString;exit;
    $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();

    return $results;
}

}
