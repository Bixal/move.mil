<?php

namespace Drupal\parser\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\parser\Service\CsvReader;
use Drupal\parser\Service\YmlReader;
use Drupal\parser\Service\ExcelReader;
use Drupal\parser\Service\DbWriter;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Class FilesAndTablesManagerForm defines the files and tables manager form.
 */
class FilesAndTablesManagerForm extends ConfigFormBase {

  /**
   * Variable containing the database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * Variables containing the entitytypemanager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity;

  /**
   * Variables containing the databaseWriter service.
   *
   * @var \Drupal\parser\Service\DbWriter
   */
  protected $writer;

  /**
   * Variables containing the CsvReader service.
   *
   * @var \Drupal\parser\Service\CsvReader
   */
  protected $csvReader;

  /**
   * Variables containing the YmlReader service.
   *
   * @var \Drupal\parser\Service\YmlReader
   */
  protected $ymlReader;

  /**
   * Variables containing the XslReader service.
   *
   * @var \Drupal\parser\Service\ExcelReader
   */
  protected $xslReader;

  /**
   * The Stream wrapper manager.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManager
   */
  protected $swm;

  /**
   * FilesAndTablesManagerForm constructor.
   *
   * Needed for dependency injection.
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              Connection $db,
                              EntityTypeManager $entity,
                              YmlReader $ymlReader,
                              CsvReader $csvReader,
                              ExcelReader $xslReader,
                              DbWriter $writer,
                              StreamWrapperManager $swm) {
    parent::__construct($config_factory);
    $this->db = $db;
    $this->entity = $entity;
    $this->writer = $writer;
    $this->csvReader = $csvReader;
    $this->ymlReader = $ymlReader;
    $this->xslReader = $xslReader;
    $this->swm = $swm;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('parser.yml_reader'),
      $container->get('parser.csv_reader'),
      $container->get('parser.xsl_reader'),
      $container->get('parser.writer'),
      $container->get('stream_wrapper_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'parser.filesandtablesmanager',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'files_and_tables_manager_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;

    $form['description'] = [
      '#markup' => '<p>' . $this->t('Manage the data used by the PPM tool uploading files and/or clearing databases.') . '</p>',
    ];

    $form['zip_3'] = [
      '#type' => 'details',
      '#title' => $this->t('Zip 3'),
    ];

    $form['zip_3']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Zip_3'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    $form['zip_3']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['zip_3']],
      '#title' => $this->t('Clear table (removes all data previously stored)'),
    ];

    $form['zip_3']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.zip3_controller_table'),
    ];

    $form['zip_5'] = [
      '#type' => 'details',
      '#title' => $this->t('Zip 5'),
    ];

    $form['zip_5']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Zip_5'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    $form['zip_5']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['zip_5']],
      '#title' => $this->t('Clear table (removes all data previously stored)'),
    ];

    $form['zip_5']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.zip5_controller_table'),
    ];

    $form['400NG'] = [
      '#type' => 'details',
      '#title' => $this->t('400NG'),
    ];

    $form['400NG']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('400NG'),
      '#upload_validators' => [
        'file_validate_extensions' => ['xlsx'],
      ],
    ];

    $form['400NG']['year'] = [
      '#type' => 'select',
      '#default_value' => NULL,
      '#title' => $this
        ->t('Select the year in which these rates are effective'),
      '#options' => [
        '2018' => $this->t('2018'),
        '2019' => $this->t('2019'),
        '2020' => $this->t('2020'),
        '2021' => $this->t('2021'),
        '2022' => $this->t('2022'),
        '2023' => $this->t('2023'),
        '2024' => $this->t('2024'),
        '2025' => $this->t('2025'),
        '2026' => $this->t('2026'),
        '2027' => $this->t('2027'),
        '2028' => $this->t('2028'),
        '2029' => $this->t('2029'),
        '2030' => $this->t('2030'),
      ],
    ];

    $form['400NG']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['400NG']],
      '#title' => $this
        ->t('Clear table (removes all data previously stored)'),
    ];

    $form['400NG']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.rates400NG_controller_links'),
    ];

    $form['entitlements'] = [
      '#type' => 'details',
      '#title' => $this->t('Entitlements'),
    ];

    $form['entitlements']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Entitlements'),
      '#upload_validators' => [
        'file_validate_extensions' => ['yml'],
      ],
    ];
    $form['entitlements']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['entitlements']],
      '#title' => $this->t('Clear table (removes all data previously stored)'),
    ];

    $form['entitlements']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.entitlements_controller_table'),
    ];

    $form['discounts'] = [
      '#type' => 'details',
      '#title' => $this->t('Discounts'),
    ];

    $form['discounts']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Discount'),
      '#upload_validators' => [
        'file_validate_extensions' => ['xlsx'],
      ],
    ];
    $form['discounts']['effective_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Please choose the effective date for this file'),
    ];

    $form['discounts']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['discounts']],
      '#title' => $this->t('Clear table (removes all data previously stored)'),
    ];

    $form['discounts']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.discounts_controller_table'),
    ];

    $form['zipcodes'] = [
      '#type' => 'details',
      '#title' => $this->t('Zip codes'),
    ];

    $form['zipcodes']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Zipcodes'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
        'file_validate_size' => [20000000],
      ],
    ];

    $form['zipcodes']['truncate'] = [
      '#type' => 'checkbox',
      '#attributes' => ['id' => ['zipcodes']],
      '#title' => $this->t('Clear table (removes all data previously stored)'),
    ];

    $form['zipcodes']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.zipcodes_controller_table'),
    ];

    unset($form['400NG']['year']['#options']['_none']);
    $form['400NG']['year']['#options'] = ['' => 'Select'] + $form['400NG']['year']['#options'];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $discounts = $form_state->getValue('discounts');

    if (!empty($discounts['file']) && empty($discounts['effective_date'])) {
      $form_state->setErrorByName('discounts', $this->t('Please fill in the effective date.'));
    }

    $ng400 = $form_state->getValue('400NG');
    if (!empty($ng400['file']) && empty($ng400['year'])) {
      $form_state->setErrorByName('year', $this->t('Please fill in the current year.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $groups = $form_state->getValues();
    $reader = NULL;
    $tables = NULL;

    foreach ($groups as $key => $group) {
      $continue = FALSE;
      $group_data = $groups[$key];
      $read_info = '';

      switch ($key) {
        case 'zip_3':
          $tables = "parser_zip3s";
          $reader = $this->csvReader;
          break;

        case 'zip_5':
          $tables = 'parser_zip5s';
          $reader = $this->csvReader;
          break;

        case '400NG':
          $date = $group_data['year'];
          $tables = [
            'parser_service_areas',
            'parser_linehauls',
            'parser_shorthauls',
            'parser_packunpacks',
          ];
          $read_info = [$key, $date];
          $reader = $this->xslReader;
          break;

        case 'entitlements':
          $tables = 'parser_entitlements';
          $reader = $this->ymlReader;
          break;

        case 'discounts':
          $date = strtotime($group_data['effective_date']);
          $tables = 'parser_discounts';
          $read_info = [$key, $date];
          $reader = $this->xslReader;
          break;

        case 'zipcodes':
          $tables = 'parser_zipcodes';
          $reader = $this->csvReader;
          break;

        default:
          $continue = TRUE;
      }

      if ($continue == TRUE) {
        continue;
      }
      $fid = array_key_exists(0, $group_data['file']) ? intval($group_data['file'][0]) : 0;
      if (array_key_exists('truncate', $group_data) && $group_data['truncate'] == 1) {
        $this->checkAndTruncate($group_data['truncate'], $tables, $key);
      }
      $this->readAndWrite($fid, $reader, $read_info, $key, $tables);
    }
  }

  /**
   * Checks and truncates the table(s).
   */
  protected function checkAndTruncate($truncate, $tables, $key) {
    if ($truncate) {
      if (is_array($tables)) {
        foreach ($tables as $table) {
          $this->db->truncate($table)
            ->execute();
        }
      }
      else {
        $this->db->truncate($tables)
          ->execute();
      }
      $this->messenger()->addMessage($key . " truncated.");
    }
  }

  /**
   * Checks FID, opens and reads the content, writes content to a custom table.
   */
  protected function readAndWrite($fid, $reader, $read_info, $key, $tables) {
    if ($fid != 0) {
      try {
        $file = $this->entity->getStorage('file')->load($fid);
        $uri = $file->getFileUri();
        $stream_wrapper_manager = $this->swm->getViaUri($uri);
        $file_path = $stream_wrapper_manager->realpath();

        if (is_array($read_info)) {
          $read_info[] = $file_path;
        }
        else {
          $read_info = $file_path;
        }
        $rawdata = $reader->parse($read_info);
        $this->writer->write($rawdata, $key, $tables);

        $this->messenger()->addMessage($key . " parsed.");
      }
      catch (\Exception $e) {
        $this->messenger()
          ->addError('Exception on file: ' . $key . ",  " . $e->getMessage());
      }
    }
  }

}
