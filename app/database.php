<?php

class Database
{
	public function __construct($data = []) 
	{
		try {
			$this->db = new PDO('mysql:host='. $data['HOST'] .';dbname=' . $data['NAME'], $data['USER'], $data['PASS']);
		}
		catch(Exception $e) {
			die(
				$e->getMessage()
			);
		}
	}

	public function row($sql, $params = []) 
	{
		return $this->prepare($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
	}

	public function prepare($sql, $params = []) 
	{
		$stmt = $this->db->prepare($sql);
		if (!empty($params)) 
		{
			foreach ($params as $key => $val) 
			{
				$stmt->bindValue(':'.$key, $val, (is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR));
			}
		}
		$stmt->execute();
		return $stmt;
	}
}