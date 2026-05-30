<?php
    ob_start();
    session_start();
    include 'config.php';
    function backup_tables($nama_file,$tables ='*')    
    {
        //$link = mysql_connect($host,$user,$pass);
        $link=opendtcek(); 
        if($tables == '*')
        {
            $tables = array();
            $result = mysqli_query($link,'SHOW TABLES FROM tokofafa');
            while($row = mysqli_fetch_row($result))
            {
                $tables[] = $row[0];
            }
        }
        else{
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        $return="";
        foreach($tables as $table)
        {
            $result = mysqli_query($link,'SELECT * FROM '.$table);
            $result2 = mysqli_query($link,'SELECT * FROM '.$table);
            $num_fields = mysqli_num_fields($result);
            $return.= 'DROP TABLE '.$table.';';
            $row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";

            if (mysqli_num_rows($result2)>0){   
                $xnum=0;
                $return.= 'INSERT INTO '.$table.' (';
                while($nm_fields = mysqli_fetch_field($result2)){
                 $xnum++;   
                 $return.=$nm_fields->name;
                 if ($xnum < ($num_fields)) { $return.= ','; } else {}
                }      
                $return.=') VALUES'."\n";
            }           
                for ($i = 0; $i < $num_fields; $i++) 
                { $xc=0;  
                    while($row = mysqli_fetch_row($result))
                    { $xc++;
                        $return.= "(";
                        for($j=0; $j < $num_fields; $j++) 
                        {
                            // $row[$j] = addslashes($row[$j]);
                            // $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                            $row[$j] = str_replace(array("\r\n\r\n","\n\r\n","\r\n","\n\n","\n"),array("\\r\\n","\\r\\n","\\r\\n","\\r\\n","\\r\\n"), addslashes($row[$j]) );
                            if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                            if ($j < ($num_fields-1)) { $return.= ','; } 
                            
                        }   
                       if ($xc==mysqli_num_rows($result)){$return.= ");\n";} else {$return.= "),\n";} 
                    }
                    
                }
                $return.="\n\n\n";
        }                            
        $nama_file;
        $handle = fopen('../backup/'.$nama_file,'w+');
        fwrite($handle,$return);
        fclose($handle);
    }
    // $host=$_POST['c_localhost'];
    // $user=$_POST['c_root'];
    // $pass=$_POST['c_pass'];
    // $name=$_POST['c_nmdb'];
    $file=$_POST['c_file'];
    backup_tables($file); 
    echo "Data telah berhasil dibackup..";
    $html = ob_get_contents(); // Masukan isi dari view.php ke dalam variabel $html
    ob_end_clean();
    echo json_encode(array('hasil'=>$html));
?>
