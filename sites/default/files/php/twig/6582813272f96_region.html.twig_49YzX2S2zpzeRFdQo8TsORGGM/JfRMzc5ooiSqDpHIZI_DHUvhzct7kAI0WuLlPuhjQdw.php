<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @belgrade/layout/region.html.twig */
class __TwigTemplate_3ede486e09fa3d6755528f6927a7f7191873e14a3360ef21db0f5b343ec99523 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 15
        echo "
";
        // line 17
        $context["classes"] = [0 => "region", 1 => ("region-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 19
($context["region"] ?? null), 19, $this->source)))];
        // line 22
        echo "
";
        // line 23
        $context["container"] = \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(($context["region_container"] ?? null), 23, $this->source));
        // line 24
        echo "
";
        // line 25
        if (($context["content"] ?? null)) {
            // line 26
            echo "  <section";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 26), 26, $this->source), "html", null, true);
            echo ">
    ";
            // line 27
            if (($context["region_container"] ?? null)) {
                // line 28
                echo "    <div class=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null), 28, $this->source), "html", null, true);
                echo "\">
      ";
            }
            // line 30
            echo "        ";
            $this->displayBlock('content', $context, $blocks);
            // line 33
            echo "      ";
            if (($context["region_container"] ?? null)) {
                // line 34
                echo "    </div>
    ";
            }
            // line 36
            echo "  </section>
";
        }
    }

    // line 30
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 31
        echo "          ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 31, $this->source), "html", null, true);
        echo "
        ";
    }

    public function getTemplateName()
    {
        return "@belgrade/layout/region.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 31,  85 => 30,  79 => 36,  75 => 34,  72 => 33,  69 => 30,  63 => 28,  61 => 27,  56 => 26,  54 => 25,  51 => 24,  49 => 23,  46 => 22,  44 => 19,  43 => 17,  40 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "@belgrade/layout/region.html.twig", "/var/www/html/themes/contrib/belgrade/templates/layout/region.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 17, "if" => 25, "block" => 30);
        static $filters = array("clean_class" => 19, "escape" => 26);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'block'],
                ['clean_class', 'escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
