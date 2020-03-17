<?php
//-----------------------------------------------------------------------------
// Data grid to show MySQL querys
//-----------------------------------------------------------------------------
// Copyright (c) 2013-2020, Kronoman
// kronoman@gmail.com
// In Loving Memory Of My Father
//-----------------------------------------------------------------------------
require_once 'connectMySQL.php';

//-----------------------------------------------------------------------------
// Actions for the table (i.e edit, view, etc)
//-----------------------------------------------------------------------------
// Make an array to set this actions on the grid
//
// real examples:
//$tableClient->actions[] = new tableAction('index.php5?module=client_edit&action=EDIT&', 'img/edit_icon.png', 'Edit client');
//$tableClient->actions[] = new tableAction('index.php5?module=receipt_enter&', 'img/money_icon.png', 'Create receiptb');
//-----------------------------------------------------------------------------
class tableAction
{

    // link
    // the primary key with name will be provided, i.e idC=1
    // set the link ready to receive the primary key, i.e "client_edit.php5?action=EDIT&" (put ? or & accordinly)
    public $link;

    // icon, i.e "img/edit_icon.png"
    public $img;

    // tooltip
    public $tooltip;

    // target , for example _blank
    public $target;

    // onclick, optional for javascript, you can add javascript actions this way
    public $onclick;

    public function __construct($link, $img, $tooltip, $target='', $onclick = '')
    {
        $this->link = $link;
        $this->img = $img;
        $this->tooltip = $tooltip;
        $this->target = $target;
        $this->onclick = $onclick;
    }
}

//-----------------------------------------------------------------------------
// Shows a data grid from a MySQL query
//-----------------------------------------------------------------------------
// TODO : we dont manage the pages yet, you should manage it on your own
// for example, using LIMIT like this:
// $cmd = 'SELECT *  FROM clientes LIMIT ' . ($page * $page_size) . ',' . $page_size;
//
// Human readable: The datatypes are converted into human readable form (booleans YES/NO, date, etc)
// CALLBACK: also you can use a callback on data, for example to replace some data with icons,
// or your custom HTML code
//-----------------------------------------------------------------------------
class kronoDataGrid
{
    // set all this before using!!

    // database config
    public $db_host = "";
    public $db_db_name = "";
    public $db_username = "";
    public $db_password = "";

    public $table_css_class = ""; // table class , for CSS ie
    public $table_id = ""; // ID for the table, i.e for use in jQuery

    public $hide_column = array(""); // columns to hide from view, i.e the primary keys array("idU", "idP");
    public $actions = array(); // action array, must be objects from tableAction
    public $PK = ""; // primary key that actions will receive

	// callback to process every data row
  // will receive the row, and should return the modified row as you wish
  // i.e function callback_de_row($row)
	public $callback_rows = NULL;

  public function __construct($db_h, $db_n, $db_u, $db_p)
  {
      $this->db_host = $db_h;
      $this->db_db_name = $db_n;
      $this->db_username = $db_u;
      $this->db_password = $db_p;
  }

    // -------------------------------------------------------------------
    // call this to query and show the table
    // the $cmd is the SQL command to excecute , i.e SELECT * FROM clients
    // will return how many rows it affected
    // -------------------------------------------------------------------
    public function queryShow($cmd)
    {
        $mysqli = connectMySQL($this->db_host, $this->db_username, $this->db_password, $this->db_db_name);

        // replace SELECT WITH SELECT SQL_CALC_FOUND_ROWS, so i can return how many rows you got, to paginate later
        // note http://quenerapu.com/mysql/me-encanta-select-found_rows/
        $cmd = str_ireplace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $cmd);

        $res = $mysqli->query($cmd); // do the query

        if ($mysqli->affected_rows < 1) // is empty?
        {
            echo "<p>Empty list.</p>";
            $mysqli->close();
            return 0;
        }

        // header
        //http://www.php.net/manual/en/mysqli-result.fetch-field.php
        echo "<table class='$this->table_css_class' id='$this->table_id'>";

        echo "<tr>";

        // header for command action column
        if (is_array($this->actions)) {
            echo "<th>";
            echo str_repeat('&nbsp', count($this->actions)); // so many spaces as actions we have
            echo "</th>";
        }

        $tipo_datos = array();

        while ($finfo = $res->fetch_field())
        {
            if (!in_array($finfo->name, $this->hide_column)) // hide what I need to hide
            {
                echo "<th>$finfo->name</th>";
                // save data type for later use
                $tipo_datos[$finfo->name] = $finfo->type;
            }
        }
        echo "</tr>";

        // table data
        while ($row = $res->fetch_assoc())
        {

            // if we have callback, call it
            if ($this->callback_rows)
                $row = call_user_func($this->callback_rows, $row);

            echo "<tr>";

            // command action column
            if (is_array($this->actions))
            {
                echo "<td>";
                foreach ($this->actions as $action)
                {
                    echo "<a onclick='$action->onclick' href='" . $action->link . $this->PK . '=' . $row[$this->PK] . "' target='$action->target' >";
                    echo "<img src='$action->img' alt='$action->tooltip' title='$action->tooltip'></a>";
                }
                echo "</td>";
            }

            // data for this row
            foreach ($row as $columna => $valor) // columna
            {
                if (!in_array($columna, $this->hide_column)) // hide as needed
                {
                    $a_mostrar = $valor;

                    // DEBUG - TODO fix, this collides with callback_rows
                    // format data types into human view
                    switch ($tipo_datos[$columna])
                    {
                        case 1: // bool
                            // SI HAY CALLBACK, VERIFICAR SI SIGUE SIENDO NUMERICO
                            if (is_numeric($valor))
                            {
                                if ($valor)
                                    $a_mostrar = 'YES';
                                else
                                    $a_mostrar = 'NO';
                            }
                            /*else
                            {
                                // asumimos que fue manipulado en el callback (lo hago en pedidos_listado por ejemplo, y lo muestro como se haya manipulado)

                            }*/
                        break;

                        case 10: //date
                        case 12: // datetime
                        case 7: // timestamp
                        case 11: // time
                        case 13: // year

                            // DEBUG TODO should check if there is a active callback and do nothing

                            // is NULL?
                            if (is_null($valor))
                                $a_mostrar = '---';
                            else
                                $a_mostrar = ucwords(strftime('%d %B %Y', strtotime($valor)));

                            /*else /// DISABLED DATE FORMAT FOR NOW - TODO FIX IT PROPERLY
                            {
                                // PEDIDO DE JORGE USAR FORMATO CORTO
                                $a_mostrar = ucwords(strftime('%d/%m/%Y', strtotime($valor)));
                                // FORMATO LARGO
                                //$a_mostrar = ucwords(strftime('%d %B %Y', strtotime($valor)));
                            }*/

                            break;
                    }

                    echo "<td>" . $a_mostrar . "</td>";

                }
            }

            echo "</tr>";
        }


        echo "</table>";

        // return how many we read, this allows you to code pagination / page handling
        $ret = $mysqli->query('SELECT FOUND_ROWS();')->fetch_array(MYSQLI_BOTH);
        $total = $ret[0];
        $mysqli->close();
        return $total;
    }

}

?>
