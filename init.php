<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Jonathan Vollebregt (jnvsor@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Kint {
    use Kint\Renderer\CliRenderer;

    final class Helpers
    {
        private function __construct()
        {
        }

        /**
         * Exit after optionally printing dump output.
         *
         * @param int|string $out
         *
         * @return never
         */
        public static function die($out): void
        {
            if (\is_string($out)) {
                echo $out;
            }

            exit;
        }

        /**
         * Alias of Kint::dump(), however the output is in plain text.
         *
         * Alias of Kint::dump(), however the output is in plain htmlescaped text
         * with some minor visibility enhancements added.
         *
         * If run in CLI colors are disabled
         *
         * @return int|string
         */
        public static function s(...$args)
        {
            if (false === Kint::$enabled_mode) {
                return 0;
            }

            $kstash = Kint::$enabled_mode;
            $cstash = CliRenderer::$cli_colors;

            if (Kint::MODE_TEXT !== Kint::$enabled_mode) {
                Kint::$enabled_mode = Kint::MODE_PLAIN;

                if (PHP_SAPI === 'cli' && true === Kint::$cli_detection) {
                    Kint::$enabled_mode = Kint::$mode_default_cli;
                }
            }

            CliRenderer::$cli_colors = false;

            $out = Kint::dump(...$args);

            Kint::$enabled_mode = $kstash;
            CliRenderer::$cli_colors = $cstash;

            return $out;
        }

        /**
         * Alias of Kint::dump(), however the output is in raw text.
         *
         * @return int|string
         */
        public static function ss(...$args)
        {
            if (false === Kint::$enabled_mode) {
                return 0;
            }

            $kstash = Kint::$enabled_mode;

            Kint::$enabled_mode = Kint::MODE_TEXT;

            $out = Kint::dump(...$args);

            Kint::$enabled_mode = $kstash;

            return $out;
        }
    }
}

namespace {
    use Kint\Helpers;
    use Kint\Kint;

    if (\defined('KINT_SKIP_HELPERS') && KINT_SKIP_HELPERS) {
        return;
    }

    if (!\function_exists('d')) {
        /**
         * Alias of Kint::dump().
         *
         * @return int|string
         */
        function d(...$args)
        {
            return Kint::dump(...$args);
        }

        Kint::$aliases[] = 'd';
    }

    if (!\function_exists('s')) {
        /**
         * Alias of Kint::dump(), however the output is in plain text.
         *
         * Alias of Kint::dump(), however the output is in plain htmlescaped text
         * with some minor visibility enhancements added.
         *
         * If run in CLI colors are disabled
         *
         * @return int|string
         */
        function s(...$args)
        {
            return Helpers::s(...$args);
        }

        Kint::$aliases[] = 's';
    }

    if (!\function_exists('ss')) {
        /**
         * Alias of Kint::dump(), however the output is in raw text.
         *
         * @return int|string
         */
        function ss(...$args)
        {
            return Helpers::ss(...$args);
        }

        Kint::$aliases[] = 'ss';
    }

    if (!\function_exists('kt')) {
        /**
         * Alias of Kint::trace().
         *
         * @return int|string
         */
        function kt()
        {
            return Kint::trace();
        }

        Kint::$aliases[] = 'kt';
    }

    if (!\function_exists('dd')) {
        /**
         * Alias of Kint::dump(). Ends the script after running.
         *
         * @return never
         */
        function dd(...$args): void
        {
            Helpers::die(Kint::dump(...$args));
        }

        Kint::$aliases[] = 'dd';
    }

    if (!\function_exists('sd')) {
        /**
         * Alias of Kint::dump(), however the output is in plain text. Ends the script after running.
         *
         * @return never
         */
        function sd(...$args): void
        {
            Helpers::die(Helpers::s(...$args));
        }

        Kint::$aliases[] = 'sd';
    }

    if (!\function_exists('ssd')) {
        /**
         * Alias of Kint::dump(). Ends the script after running.
         *
         * @return never
         */
        function ssd(...$args): void
        {
            Helpers::die(Helpers::ss(...$args));
        }

        Kint::$aliases[] = 'ssd';
    }

    if (!\function_exists('ktd')) {
        /**
         * Alias of Kint::trace(). Ends the script after running.
         *
         * @return never
         */
        function ktd(): void
        {
            Helpers::die(Kint::trace());
        }

        Kint::$aliases[] = 'ktd';
    }
}
