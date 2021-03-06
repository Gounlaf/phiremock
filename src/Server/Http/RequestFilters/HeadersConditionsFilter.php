<?php
/**
 * This file is part of Phiremock.
 *
 * Phiremock is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Phiremock is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Phiremock.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Mcustiel\Phiremock\Server\Http\RequestFilters;

use Mcustiel\SimpleRequest\Exception\FilterErrorException;
use Mcustiel\SimpleRequest\Filter\AbstractEmptySpecificationFilter;
use Mcustiel\SimpleRequest\Interfaces\FilterInterface;

class HeadersConditionsFilter extends AbstractEmptySpecificationFilter implements FilterInterface
{
    /**
     * @var ConvertToCondition
     */
    private $conditionFilter;

    public function __construct()
    {
        $this->conditionFilter = new ConvertToCondition();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Mcustiel\SimpleRequest\Interfaces\FilterInterface::filter()
     */
    public function filter($value)
    {
        if (null === $value) {
            return null;
        }
        $this->checkValueIsArrayOrThrowException($value);

        $return = [];
        foreach ($value as $header => $condition) {
            $return[$header] = $this->conditionFilter->filter($condition);
        }

        return $return;
    }

    /**
     * @param mixed $value
     *
     * @throws FilterErrorException
     */
    private function checkValueIsArrayOrThrowException($value)
    {
        if (!\is_array($value)) {
            throw new FilterErrorException('Error trying to parse headers condition. It should be a collection.');
        }
    }
}
