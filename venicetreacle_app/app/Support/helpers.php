<?php

use Illuminate\Http\Request;

function get_search_persister_page_hash() {

    return md5(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)); //get the page without the get parameters
}

function search_persister(Request $request, $pageHash = '') {

    // Nick 2022-07-05 will remember the last search for a particular page and apply it
    // We can pass in a page hash to get the params for a different page (e.g. /users for /users/export)

    if(!$pageHash) {
        $pageHash = get_search_persister_page_hash();
    }

    if(count($request->all()) > 0) {

        // yes request - store it

        Session::put($pageHash, $request->all());
    }
    else {

        // no request - if it has been stored, get it

        $params = Session::get($pageHash);
        if($params) {
            foreach($params as $key => $value) {

                $request[$key] = $value;
            }
        }
    }

    return $request;
}


function exportCSV($filename, $fields, $query) {

    $csvPath = @tempnam('/tmp', $filename . '-');
    unlink($csvPath);
    $csvPath .= '.csv';
    $fh = fopen($csvPath, 'w');
    fputcsv($fh, array_keys($fields), ',', '"');
    $query->chunk(200, function($listItems) use($fh, $fields) {
    
        foreach($listItems as $item) {
        
            //dd(($item));

            $row = [];
            foreach($fields as $field => $fieldName) {
            
                // hack for full_name which isnt there for merchant
                if($fieldName == 'full_name') {
                
            //        dd($item->$fieldName);
                }

                // Nick: this chain separator hack allows to use Eloquent to get fields from joined objects
                // rather than having to write the SQL and inner joins
                // there is probably a "laravel" way but it is not apparent
                //
                // currently goes three chains deep, but of course we can go deeper if needed

                $fieldNameChain = explode('->', $fieldName);
                switch(sizeof($fieldNameChain)) {
                    case 1:
                        $row[] = $item->$fieldName;
                        break;
                    case 2:
                        if($item->{$fieldNameChain[0]}) {
                            $row[] = $item->{$fieldNameChain[0]}->{$fieldNameChain[1]};
                        }
                        else
                        {
                            $row[] = '';
                        }
                        break;
                    case 3:
                        if($item->{$fieldNameChain[0]} && $item->{$fieldNameChain[0]}->{$fieldNameChain[1]}) {
                            $row[] = $item->{$fieldNameChain[0]}->{$fieldNameChain[1]}->{$fieldNameChain[2]};
                        }
                        else
                        {
                            $row[] = '';
                        }
                        break;
                }

            }
            fputcsv($fh, $row);
        }
    });
    fclose($fh);

    header('Content-length: ' . filesize($csvPath));
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    header('Content-type: application/vnd.ms-excel');
    readfile($csvPath);
    die;
}

function deleteFolderAndContents($folderPath) {

    $files = glob($folderPath . '/*'); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file); // delete file
        }
    }
    rmdir($folderPath); // delete the folder
}