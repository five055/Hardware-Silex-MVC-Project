


      <div>

        <label class="center"><strong>Tout les Produits Récuperés</strong> </label>
      </div>
      <!-- <input type="text" name="varrecher" value="" placeholder="Rechercher"> -->

 <?php


include 'AmazonAdv.php';
include 'AmazonAdvItems.php';
include 'AmazonAdvItem.php';
include 'AmazonAdvImage.php';


use hardwarefinal\AmazonAdv;
use hardwarefinal\AmazonAdvItems;
use hardwarefinal\AmazonAdvItem;
use hardwarefinal\AmazonAdvImage;



$varrecher='carte video msi';
$AnnoncesBooks = AmazonAdv::NewRequest()
    ->addKeyword($varrecher)
    ->setSearchIndex(AmazonAdv::PC_HARDWARE)
    ->request();
?>


<div>
 <?php
    $tableau = [];
    foreach($AnnoncesBooks->Items as $annonce)
    {


$annonces[] = [
  'TITREANNONCE'  => $annonce->Title,
  'IMAGEANNONCE'  => $annonce->getImage(AmazonAdvItem::IMAGE_MEDIUM)->URL,
  'AUTEURANNONCE' => $annonce->getAuthor(),
  'TARIFANNONCE'  => $annonce->getPriceWithCurrency()
];


echo 'Titre : <a href="'.$annonce->URL.'" title="'.$annonce->Title.'">'.$annonce->Title.'</a><br />';
echo '<img atl="'.$annonce->Title.'" src=" '.$annonce->getImage(AmazonAdvItem::IMAGE_MEDIUM)->URL.'" /><br />';
echo '<em>Réalisé par '.$annonce->getAuthor().'</em><br />';
echo '<em>Prix : '.$annonce->getPriceWithCurrency().'</em><br />';
    }

  //
  //   echo'<pre>==========================================================';
  // print_r ($annonces);
  //     echo'==========================================================</pre>';



  foreach($annonces as $annonce)
  {
      $t[]= $annonce['TARIFANNONCE'].'<br>';
  }
    natcasesort($t);
    echo '-------------------';
    print_r ($t);
    echo '-------------------';


    echo '<a href="'.$AnnoncesBooks->MoreSearchResultsUrl.'">Voir plus de produits '.$varrecher.'</a>';


  ?>

</div>
