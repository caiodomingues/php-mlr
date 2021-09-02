<?php

/**
 * Formulas
 *
 * Simple Linear Regression (SLR)
 * Y = a + b1x1
 *
 * Where:
 * Y = Dependent Variable
 * A = Constant (Intercept of Y axis)
 * B = Coefficient of variable
 *
 * Multiple Linear Regression (MLR)
 * Y = a + b1x1 + b2x2 + ... + bkxk
 *
 * Where:
 * Y = Depent Variable
 * X = Independent Variable
 * A = Expected value of Y when all independent variables (X) are null
 * B = Expected variation of Y given a unit increment in X, keeping the other independent variables constant
 */

function arrayAverage($arr = [])
{
  return array_sum($arr) / count($arr);
}

function arraySigma($arr = [], $funct = null)
{
  if (is_null($funct)) {
    return array_sum($arr);
  } else {
    $val = 0;
    foreach ($arr as $k => $i) {
      $val += $funct($i);
    }
    return $val;
  }
}

function getData($data)
{
  $result = [];
  $row = 1;

  if (($handle = fopen($data, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);
      $row++;

      for ($c = 0; $c < $num; $c++) {
        $result[] = $data[$c];
      }
    }
    fclose($handle);
  }

  return $result;
}

function getTable($data)
{
  $row = 1;
  $table = '<table>';

  if (($handle = fopen($data, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);
      $row++;

      $table .= '<tr>';
      for ($c = 0; $c < $num; $c++) {
        if ($row === 2) {
          $table .= '<th>' . $data[$c] . '</th>';
        } else {
          $table .= '<td>' . $data[$c] . '</td>';
        }
      }
      $table .= '</tr>';
    }
    fclose($handle);
  }

  $table .= '</table>';
  return $table;
}

$students = [2, 6, 8, 8, 12, 16, 20, 20, 22, 26];
$sales = [58, 105, 88, 118, 117, 137, 157, 169, 149, 202];

$y = arrayAverage($sales);
$x = arrayAverage($students);

$b1Inclination = arraySigma(range(0, 9), function ($i) use ($students, $sales, $x, $y) {
  return ($students[$i] - $x) * ($sales[$i] - $y);
});

$b1Division = arraySigma(range(0, 9), function ($i) use ($students, $x) {
  return pow(($students[$i] - $x), 2);
});

$b1 = $b1Inclination / $b1Division;
$b0 = $y - $b1 * $x;

?>

<style>
  * {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  table td,
  table th {
    border: 1px solid #ddd;
    padding: 8px;
  }

  table tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  table tr:hover {
    background-color: #ddd;
  }

  table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
  }

  section {
    margin: 32px;
  }

  hr {
    margin: 32px;
  }

  blockquote {
    background-color: #f3f3f3;
    padding: 16px;
    border-radius: 5px;
  }
</style>

<section>
  <h1>Simple Linear Regression</h1>
  <p> Y = a + b<sub>1</sub>x<sub>1</sub></p>
  <small><i> Y = Sales, X = Population </i></small>
  <hr />

  <b> Dataset: </b>
  <?= getTable("data.csv") ?>

  <h2> Calculating manually: </h2>
  <p>
    Using the columns (variables) in formula, we do have: <br />
    <i><b>+i</b></i> = Restaurant Unit. <br />
    <i><b>+x<sub>i</sub></b></i> = Student Population. <br />
    <i><b>+y<sub>i</sub></b></i> = Quarter Sales (x R$1.000,00). <br />
  </p>
  <h3>Average of quarter sales (<i><b>y</b></i>)</h3>
  <p>We have 10 samples, so <i><b>n = 10</b></i>:</p>
  <blockquote>
    y = (58 + 105 + 88 + 118 + 117 + 137 + 157 + 169 + 149 + 202) / 10
    <br />
    y = 1300/10
    <br />
    y = 130
  </blockquote>
  <p>Average sale (10 samples) is <i><b>y = 130</b></i>.

  <h3>Average of student population (<i><b>x</b></i>)</h3>
  <blockquote>
    x = (2 + 6 + 8 + 8 + 12 + 16 + 20 + 20 + 22 + 26) / 10
    <br />
    x = 140/10
    <br />
    x = 14
  </blockquote>
  <p>Average student population (10 samples) is <i><b>x = 14</b></i>.

  <h3>Going further</h3>
  <p>To reach <i><b>y = b<sub>0</sub> + b<sub>1</sub> * x</b></i>, first we need to get <i><b>b<sub>1</sub></b></i> inclination, then the constant <i><b>b<sub>0</sub></b></i>:</p>
  <blockquote>
    For both ∑, use <i>n = 10</i> and <i>i = 0</i>
    <br />
    <br />

    b<sub>1</sub> = ∑ (x<sub>i</sub>−x) ∗ (y<sub>i</sub>−y) / ∑ (x<sub>i</sub>−x)<sup>2</sup>
    <br />
    b<sub>1</sub> = 2.840 / 568
    <br />
    b<sub>1</sub> = 5
  </blockquote>

  <p>Now we can get <i><b>b<sub>0</sub></b></i>:</p>
  <blockquote>
    b<sub>0</sub> = y - b<sub>1</sub> * x
    <br />
    b<sub>0</sub> = 130 - 5 * (14)
    <br />
    b<sub>0</sub> = 60
  </blockquote>

  <p>With these data, let's assume that we are going to open a new restaurant unit close to a population of 16 thousand students:</p>
  <blockquote>
    y = 60 + 5 * x
    <br />
    <br />
    <p>Simulating:</p>
    y = b<sub>0</sub> + b<sub>1</sub> * (x)
    <br />
    y = 60 + 5 * (16)
    <br />
    y = 140 (or R$140.000,00)
  </blockquote>

  <p>If you look at dataset, Restaurant Unit 6 have the same 16 thousand student population, but got R$137.000,00. The <b>error coefficient is ±5%, which is good</b>. Look:</p>

  <table>
    <tbody>
      <tr>
        <th>Restaurant</th>
        <th>Student Population (thousand)</th>
        <th>Sales (thousand)</th>
      </tr>
      <tr>
        <td>11 (estimated)</td>
        <td>16</td>
        <td>140</td>
      </tr>
      <tr>
        <td>6</td>
        <td>16</td>
        <td>137</td>
      </tr>
    </tbody>
  </table>
</section>

<!-- <section>
  <h1>Multiple Linear Regression</h1>
  <p> Y = a + b<sub>1</sub>x<sub>1</sub> + b<sub>2</sub>x<sub>2</sub> + ... + b<sub>k</sub>x<sub>k</sub></p>
  <small><i> Y = Sales, X<sub>1</sub> = Population, X<sub>2</sub> = Distance </i></small>
  <hr />

  <b> Dataset: </b>
  <?= getTable("multipleData.csv") ?>
</section> -->
