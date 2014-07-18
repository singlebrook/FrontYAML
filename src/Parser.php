<?php
/**
 * FrontYAML
 *
 * @copyright Matthieu Napoli http://mnapoli.fr
 * @license   http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Mni\FrontYAML;

use Mni\FrontYAML\Bridge\Symfony\SymfonyYAMLParser;
use Mni\FrontYAML\YAML\YAMLParser;

/**
 * YAML Front matter parser
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Parser
{
    /**
     * @var YAMLParser
     */
    private $yamlParser;

    /**
     * @var MarkdownParser
     */
    private $markdownParser;

    public function __construct(YAMLParser $yamlParser = null, MarkdownParser $markdownParser = null)
    {
        $this->yamlParser = $yamlParser ?: new SymfonyYAMLParser();
        $this->markdownParser = $markdownParser ?: FALSE;
    }

    /**
     * Parse a string containing the YAML front matter and the markdown.
     *
     * @param string $str
     *
     * @return Document
     */
    public function parse($str)
    {
        $lines = explode(PHP_EOL, $str);

        if (count($lines) <= 1) {
            if ($this->markdownParser) {
                $str = $this->markdownParser->parse($str);
            }
            return new Document(null, $str);
        }

        if (rtrim($lines[0]) !== '---') {
            if ($this->markdownParser) {
                $str = $this->markdownParser->parse($str);
            }
            return new Document(null, $str);
        }

        // There is a Front matter
        unset($lines[0]);
        $yaml = array();
        $i = 1;

        foreach ($lines as $line) {
            if ($line === '---') {
                break;
            }

            $yaml[] = $line;
            $i++;
        }

        $yaml = $this->yamlParser->parse(implode(PHP_EOL, $yaml));
        $content = implode(PHP_EOL, array_slice($lines, $i));

        if ($this->markdownParser) {
            $content = $this->markdownParser->parse($content);
        }

        return new Document($yaml, $content);
    }
}
