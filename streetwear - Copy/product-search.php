$search = $_GET['search'];

if(stripos($productName,$search) !== false)
{
    echo $productName;
}