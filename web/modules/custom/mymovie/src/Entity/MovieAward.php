<?php

declare(strict_types = 1);

namespace Drupal\mymovie\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\mymovie\MovieAwardInterface;

/**
 * Defines the movie award entity type.
 *
 * @ConfigEntityType(
 *   id = "movie_award",
 *   label = @Translation("Movie Award"),
 *   label_collection = @Translation("Movie Awards"),
 *   label_singular = @Translation("movie award"),
 *   label_plural = @Translation("movie awards"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie award",
 *     plural = "@count movie awards",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\mymovie\MovieAwardListBuilder",
 *     "form" = {
 *       "add" = "Drupal\mymovie\Form\MovieAwardForm",
 *       "edit" = "Drupal\mymovie\Form\MovieAwardForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *   },
 *   config_prefix = "movie_award",
 *   admin_permission = "administer movie_award",
 *   links = {
 *     "collection" = "/admin/structure/movie-award",
 *     "add-form" = "/admin/structure/movie-award/add",
 *     "edit-form" = "/admin/structure/movie-award/{movie_award}",
 *     "delete-form" = "/admin/structure/movie-award/{movie_award}/delete",
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "year" = "year",
*      "movie_node" = "movie_node",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "year",
 *      "movie_node",
 *   },
 * )
 */
final class MovieAward extends ConfigEntityBase implements MovieAwardInterface {

  /**
   * The ID of movie award.
   */
  protected string $id;

  /**
   * The label of movie award.
   */
  protected string $label;

  /**
   * The description of movie award.
   */
  protected string $description;

  /**
   * The year of movie.
   */
  protected int $year;

  /**
   * The name of the movie.
   */
  protected string $movie_node;

  /**
   * Gets the year the movie won the award.
   *
   * @return int
   *   The year the movie won the award.
   */
  public function getYear() {
    return $this->year;
  }

  /**
   * Sets the year the movie won the award.
   *
   * @param int $year
   *   The year the movie won the award.
   *
   * @return $this
   */
  public function setYear($year) {
    $this->year = $year;
    return $this;
  }

}
