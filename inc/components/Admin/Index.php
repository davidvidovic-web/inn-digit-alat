<?php

namespace InnDigit\Components\Admin;

global $wpdb;
$table = $wpdb->prefix . 'inndigit';

$sql = "SELECT * FROM $table";
$results = $wpdb->get_results($sql);



foreach ($results as $result) {
  $result->finansije_q = unserialize($result->finansije_q);
  $result->finansije_a = unserialize($result->finansije_a);
  $result->ljudski_resursi_q = unserialize($result->ljudski_resursi_q);
  $result->ljudski_resursi_a = unserialize($result->ljudski_resursi_a);
  $result->marketing_q = unserialize($result->marketing_q);
  $result->marketing_a = unserialize($result->marketing_a);
  $result->proces_q = unserialize($result->proces_q);
  $result->proces_a = unserialize($result->proces_a);
  $result->strategija_q = unserialize($result->strategija_q);
  $result->strategija_a = unserialize($result->strategija_a);
}




?>


<div class="table">
  <div class="table-header">
    <div>Naziv privrednog društva</div>
    <div>Kontakt email</div>
    <div>Datum</div>
  </div>
  <div class="table-content">
    <?php foreach ($results as $result) {
      echo '<div class="table-content-entry-wrapper">';
      echo '<div class="table-content-header">';
      echo '<div>' . $result->naziv_privrednog_drustva . '</div>';
      echo '<div>' . $result->email . '</div>';
      echo '<div>' . $result->datum . '</div>';
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
      echo '<div class="footer-actions"><a target="_blank" href="/wp-content/plugins/inn-digit-alat/pdfs/' . $result->naziv_privrednog_drustva . '-rezultati-' . $sqlDate[0] . '.pdf"><button class="pdf-button">Pogledaj rezultat</button></a></div>';
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
  }

  .table-header>div:nth-child(2) {
    border-left: 1px solid;
    border-right: 1px solid;
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

  .table .table-header>div:nth-child(3) {
    border-top-right-radius: 8px;
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
</script>