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

/* themes/contrib/belgrade/templates/layout/page.html.twig */
class __TwigTemplate_d6dfed2ca9e7986607d8bd3c5a4356df648082532497cdeddfc4c8f8c43ad14d extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'main' => [$this, 'block_main'],
            'footer' => [$this, 'block_footer'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 45
        echo "<div class=\"layout-container font-smoothing\">

  ";
        // line 47
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "top_bar", [], "any", false, false, true, 47), 47, $this->source), "html", null, true);
        echo "

  <header role=\"banner\">
    ";
        // line 50
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
        echo "
  </header>

  ";
        // line 53
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "breadcrumb", [], "any", false, false, true, 53), 53, $this->source), "html", null, true);
        echo "

  ";
        // line 55
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 55), 55, $this->source), "html", null, true);
        echo "

  ";
        // line 57
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "help", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
        echo "

  ";
        // line 59
        $this->displayBlock('main', $context, $blocks);
        // line 84
        echo "
  ";
        // line 85
        $this->displayBlock('footer', $context, $blocks);
        // line 92
        echo "
  ";
        // line 93
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "navigation", [], "any", false, false, true, 93), 93, $this->source), "html", null, true);
        echo "

</div>
";
    }

    // line 59
    public function block_main($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 60
        echo "    ";
        if (($context["main_container"] ?? null)) {
            // line 61
            echo "      <div class=\"main-container ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["main_container"] ?? null), 61, $this->source), "html", null, true);
            echo "\">
    ";
        }
        // line 63
        echo "
      <main role=\"main\" ";
        // line 64
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 64, $this->source), "html", null, true);
        echo ">
        <a id=\"main-content\" tabindex=\"-1\" aria-hidden=\"true\"></a>

        <div class=\"row\">
          ";
        // line 68
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 68), 68, $this->source), "html", null, true);
        echo "

          ";
        // line 70
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 70)) {
            // line 71
            echo "            ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
            echo "
          ";
        }
        // line 73
        echo "
          ";
        // line 74
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 74)) {
            // line 75
            echo "            ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 75), 75, $this->source), "html", null, true);
            echo "
          ";
        }
        // line 77
        echo "        </div>
      </main>

    ";
        // line 80
        if (($context["main_container"] ?? null)) {
            // line 81
            echo "      </div>
    ";
        }
        // line 83
        echo "  ";
    }

    // line 85
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 86
        echo "    ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 86)) {
            // line 87
            echo "      <footer role=\"contentinfo\">
        ";
            // line 88
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 88), 88, $this->source), "html", null, true);
            echo "
      </footer>
    ";
        }
        // line 91
        echo "  ";
    }

    public function getTemplateName()
    {
        return "themes/contrib/belgrade/templates/layout/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 91,  162 => 88,  159 => 87,  156 => 86,  152 => 85,  148 => 83,  144 => 81,  142 => 80,  137 => 77,  131 => 75,  129 => 74,  126 => 73,  120 => 71,  118 => 70,  113 => 68,  106 => 64,  103 => 63,  97 => 61,  94 => 60,  90 => 59,  82 => 93,  79 => 92,  77 => 85,  74 => 84,  72 => 59,  67 => 57,  62 => 55,  57 => 53,  51 => 50,  45 => 47,  41 => 45,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/belgrade/templates/layout/page.html.twig", "/var/www/html/themes/contrib/belgrade/templates/layout/page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("block" => 59, "if" => 60);
        static $filters = array("escape" => 47);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['block', 'if'],
                ['escape'],
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
