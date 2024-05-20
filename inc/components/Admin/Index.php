<?php

namespace InnDigit\Components\Admin;

global $wpdb;
$table = $wpdb->prefix . 'inndigit';

$sql = "SELECT * FROM $table ORDER BY datum DESC";
$results = $wpdb->get_results($sql);



foreach ($results as $result) {
  $result->finansije_q = json_decode($result->finansije_q);
  $result->finansije_a = json_decode($result->finansije_a);
  $result->ljudski_resursi_q = json_decode($result->ljudski_resursi_q);
  $result->ljudski_resursi_a = json_decode($result->ljudski_resursi_a);
  $result->marketing_q = json_decode($result->marketing_q);
  $result->marketing_a = json_decode($result->marketing_a);
  $result->proces_q = json_decode($result->proces_q);
  $result->proces_a = json_decode($result->proces_a);
  $result->strategija_q = json_decode($result->strategija_q);
  $result->strategija_a = json_decode($result->strategija_a);
}




?>


<div class="table">
  <div class="table-header">
    <div>Naziv privrednog društva</div>
    <div>Kontakt email</div>
    <div>Datum</div>
    <div style="width: 10%;"><img style="width: 16px; height: 16px;" src="<?php echo PLUGIN_URL . 'assets/icons/trash.svg' ?>"></div>
    <div style="width: 10%;"><img style="width: 16px; height: 16px;" src="<?php echo PLUGIN_URL . 'assets/icons/excel.svg' ?>"></div>
  </div>
  <div class=" table-content">
    <?php foreach ($results as $result) {
      echo '<div class="table-content-entry-wrapper">';
      echo '<div class="table-content-header">';
      echo '<div style="display: none;" id="' . $result->id . '"></div>';
      echo '<div>' . $result->naziv_privrednog_drustva . '</div>';
      echo '<div>' . $result->email . '</div>';
      echo '<div>' . $result->datum . '</div>';
      echo '<div style="width: 10%;">' . '<a href="#" onClick="removeItem(' . $result->id . ')">' . '<img style="width: 16px; height: 16px;" src="' . PLUGIN_URL . 'assets/icons/trash.svg"></a>' . '</div>';
      echo '<div style="width: 10%;">' . '<a href="#" onClick="createExcel(' . $result->id . ')">' . '<img style="width: 16px; height: 16px;" src="' . PLUGIN_URL . 'assets/icons/excel.svg"></a>' . '</div>';
      echo '</div>';
      echo '<div class="table-content-data">';

      echo '<div class="table-section strategy-section">';
      echo '<h1> Strategija </h1>';
      foreach ($result->strategija_q as $key => $question) {
        $answers = $result->strategija_a[$key];
        if ($question !== 'Veličina privrednog društva (odaberite od ponuđenih):') {
          echo '<div class="table-section-header"><h3>'  . $question . '</h3></div>';
          echo '<div class="table-section-answers">';
          $prev_answer = '';
          foreach ($answers as $key => $answer) {
            $key = $key + 1;
            if ($prev_answer !== $answer) {
              $prev_answer = $answer;
              echo '<div class="answer">' . $key . '. ' . $answer . '</div>';
            }
          }
          echo '</div>';
        }
      }
      echo '</div>';

      echo '<div class="table-section proces-section">';
      echo '<h1> Proces </h1>';
      foreach ($result->proces_q as $key => $question) {
        $answers = $result->proces_a[$key];
        if ($question !== 'Veličina privrednog društva (odaberite od ponuđenih):') {
          echo '<div class="table-section-header"><h3>'  . $question . '</h3></div>';
          echo '<div class="table-section-answers">';
          $prev_answer = '';
          foreach ($answers as $key => $answer) {
            $key = $key + 1;
            if ($prev_answer !== $answer) {
              $prev_answer = $answer;
              echo '<div class="answer">' . $key . '. ' . $answer . '</div>';
            }
          }
          echo '</div>';
        }
      }
      echo '</div>';

      echo '<div class="table-section hr-section">';
      echo '<h1> Ljudski resursi </h1>';
      foreach ($result->ljudski_resursi_q as $key => $question) {
        $answers = $result->ljudski_resursi_a[$key];
        echo '<div class="table-section-header"><h3>'  . $question . '</h3></div>';
        echo '<div class="table-section-answers">';
        foreach ($answers as $key => $answer) {
          $key = $key + 1;
          echo '<div class="answer">' . $key . '. ' . $answer . '</div>';
        }
        echo '</div>';
      }
      echo '</div>';

      echo '<div class="table-section marketing-section">';
      echo '<h1> Marketing </h1>';
      foreach ($result->marketing_q as $key => $question) {
        $answers = $result->marketing_a[$key];
        echo '<div class="table-section-header"><h3>'  . $question . '</h3></div>';
        echo '<div class="table-section-answers">';
        foreach ($answers as $key => $answer) {
          $key = $key + 1;
          echo '<div class="answer">' . $key . '. ' . $answer . '</div>';
        }
        echo '</div>';
      }
      echo '</div>';

      echo '<div class="table-section finansije-section">';
      echo '<h1> Finansije </h1>';
      foreach ($result->finansije_q as $key => $question) {
        $answers = $result->finansije_a[$key];
        echo '<div class="table-section-header"><h3>'  . $question . '</h3></div>';
        echo '<div class="table-section-answers">';
        foreach ($answers as $key => $answer) {
          $key = $key + 1;
          echo '<div class="answer">' . $key . '. ' . $answer . '</div>';
        }
        echo '</div>';
      }
      echo '</div>';
      echo '<div class="table-footer">';
      $sqlDate = $result->datum;
      $sqlDate = explode(' ', $sqlDate);
      echo '<p> * Ukoliko je privredno društvo poslalo više upitnika u toku jednog dana biće kreiran samo inicijalni PDF </p>';
      $companyName = str_replace(" ", "", $result->naziv_privrednog_drustva);
      $companyName = preg_replace("/[^\w\s]/", "", $companyName);
      echo '<div class="footer-actions"><a target="_blank" href="/wp-content/plugins/inn-digit-alat/pdfs/InnDigit-ALAT-' . $companyName . '-rezultati-' . $sqlDate[0] . '.pdf"><button class="pdf-button">Pogledaj rezultat</button></a></div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }
    echo '</div>';
    ?>
  </div>
</div>


<style>
  .table {
    padding: 20px;
  }

  .table .table-header {
    display: flex;
    border: 1px solid;
    border-bottom: none;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
  }

  .table .table-header>div {
    width: 33%;
    font-size: 1.2em;
    font-weight: bold;
    text-align: center;
    padding: 20px;
  }

  .table-content {
    border: 1px solid;
  }

  .table-content-header {
    display: flex;
  }

  .table-content-header>div {
    width: 33%;
    font-size: 1.2em;
    font-weight: bold;
    text-align: center;
    padding: 20px;
    align-items: center;
    justify-content: center;
    display: flex;
    min-height: 86px;
  }

  .table-content-header>div>a {
    position: relative;
    transition: all 0.3s ease;
    padding: 0;
  }

  .table-content-header>div>a:hover {
    background: #0073aa;
    padding: 15px 20px;
    border-radius: 8px;
    filter: invert(1);
    transition: all 0.3s ease;
  }

  .table-header>div:nth-child(2) {
    border-left: 1px solid;
    border-right: 1px solid;
  }

  .table-header>div:nth-child(3) {
    border-right: 1px solid;
    border-radius: 0;
  }

  .table-content-data {
    flex-wrap: wrap;
    gap: 2em;
    display: none;
    transition: 0.6s all ease-in-out;
    padding: 30px;
  }

  .table-section {
    width: 45%;
    border: 1px solid;
    padding: 10px;
    border-radius: 8px;
    ;
  }

  .table-section-answers {
    display: flex;
    flex-direction: column;
    gap: 5px;
    font-size: 14px;
  }

  .display {
    display: flex;
    transition: 0.6s all ease-in-out;
  }

  #wpfooter {
    display: none;
  }

  .table-footer {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid;
    padding-top: 10px;
  }

  .footer-actions {
    display: flex;
    gap: 10px;
  }

  .pdf-button {
    padding: 10px 20px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 8px;
  }

  .table .table-header>div:nth-child(1) {
    border-top-left-radius: 8px;
  }

  .table-content-entry-wrapper:nth-last-child() .table-content-header>div:nth-child(1) {
    border-bottom-left-radius: 8px;
  }

  .table-content .table-content-entry-wrapper:nth-child(even) {
    background-color: #dedede;
    background-color: #0073aa;
  }

  .table-content-entry-wrapper:nth-child(even) .table-content-header,
  .table-content-entry-wrapper:nth-child(even) h1,
  .table-content-entry-wrapper:nth-child(even) h3,
  .table-content-entry-wrapper:nth-child(even) .answer,
  .table-content-entry-wrapper:nth-child(even) .table-footer p {
    color: #fff;
  }

  .table-content-entry-wrapper:nth-child(even) .table-section {
    border: 1px solid #fff;
  }

  .table-content-entry-wrapper:nth-child(even) .table-footer {
    border-top: 1px solid #fff;
  }

  .table-content-entry-wrapper:nth-child(even) .pdf-button {
    background: #fff;
    color: #0073aa;
  }

  .table-content-entry-wrapper:nth-child(even) img {
    filter: invert(1);
  }

  .table-content-entry-wrapper:nth-child(even) a:hover {
    filter: invert(0);
    background: #d98a4f;
  }

  a button:hover,
  .table-content-entry-wrapper:hover {
    cursor: pointer;
  }
</style>

<script>
  jQuery(document).ready(function($) {
    $('.table-content-entry-wrapper').each(function() {
      $(this).click(function() {
        $(this).children('.table-content-data').toggleClass('display');
      })
    });

    $('.table-section-header').each(function() {
      $(this).click(function() {
        $(this).next('table-section-answers').toggleClass('display');
      })
    })
  });

  function removeItem(id) {
    event.preventDefault();
    event.stopPropagation();
    let ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

    jQuery.post(
      ajaxUrl, {
        action: "remove_quiz_item",
        data: id,
      },
      function(response) {
        if (response.success) {
          var parent = jQuery('div#' + id).parent().parent();
          parent.fadeOut();
        } else {
          console.log(id); // error message
        }
      }
    );
  }

  function createExcel(id) {
    event.preventDefault();
    event.stopPropagation();
    let ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';

    jQuery.post(
      ajaxUrl, {
        action: "create_excel",
        data: id,
      },
      function(response) {
        if (response.success) {
          console.log(response.data);
        } else {
          console.log(id); // error message
        }
      }
    );
  }
</script>