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

/* themes/contrib/belgrade/templates/layout/region--top-bar.html.twig */
class __TwigTemplate_9068dd512201a1daa78ddfb0e07e06c87a5c22d70e6c1d250123b06eacd08752 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doGetParent(array $context)
    {
        // line 17
        return "@belgrade/layout/region.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 15
        $macros["svg"] = $this->macros["svg"] = $this->loadTemplate("@belgrade/macros.twig", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", 15)->unwrap();
        // line 19
        twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "setAttribute", [0 => "role", 1 => "navigation"], "method", false, false, true, 19);
        // line 17
        $this->parent = $this->loadTemplate("@belgrade/layout/region.html.twig", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", 17);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 21
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 22
        echo "  <div class=\"d-flex align-items-center justify-content-between fw-light\">
    <a class=\"navigation-toggle me-auto cursor-pointer\" data-bs-toggle=\"offcanvas\" data-bs-target=\"#navigationRegion\" aria-controls=\"navigationRegion\">
      ";
        // line 24
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["svg"], "macro_getIcon", ["list", 32, 32], 24, $context, $this->getSourceContext()));
        echo "
      ";
        // line 25
        if (($context["navigation_toggle_text"] ?? null)) {
            // line 26
            echo "        <span>";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["navigation_toggle_text"] ?? null), 26, $this->source), "html", null, true);
            echo "</span>
      ";
        }
        // line 28
        echo "    </a>
    ";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 29, $this->source), "html", null, true);
        echo "
  </div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 29,  73 => 28,  67 => 26,  65 => 25,  61 => 24,  57 => 22,  53 => 21,  48 => 17,  46 => 19,  44 => 15,  37 => 17,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/belgrade/templates/layout/region--top-bar.html.twig", "/var/www/html/themes/contrib/belgrade/templates/layout/region--top-bar.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 15, "do" => 19, "if" => 25);
        static $filters = array("escape" => 26);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['import', 'do', 'if'],
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
