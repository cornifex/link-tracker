<?php

namespace Drupal\link_tracker\Routing;

use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class TrackedLinkSubscriber implements EventSubscriberInterface {

  protected RouteMatchInterface $currentRouteMatch;

  public function __construct($current_route_match) {
    $this->currentRouteMatch = $current_route_match;
  }

  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'trackLinkVisit',
    ];
  }

  public function trackLinkVisit() {
    if ($this->currentRouteMatch->getRouteName() == 'entity.link_tracker_tracked_link.canonical') {
      $entity = $this->currentRouteMatch->getParameter('link_tracker_tracked_link');
      // @todo Track the link visit by creating a tracked_link_visit entity.
    }
  }

}