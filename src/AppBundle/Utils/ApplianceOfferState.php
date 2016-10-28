<?php
/**
 * Created by IntelliJ IDEA.
 * User: sergi
 * Date: 27/09/16
 * Time: 13:35
 */

namespace AppBundle\Utils;


class ApplianceOfferState
{
    const NEW_OPPORTUNITY = "new_opportunity";
    const SENT_OFFER = "sent_offer";
    const REPLIED = "replied";
    const WITHDRAWN = "withdrawn";
    const WON = "won";
    const LOST = "lost";
    const EXPIRED = "expired";
    const NEW_MESSAGE = "new message";
}