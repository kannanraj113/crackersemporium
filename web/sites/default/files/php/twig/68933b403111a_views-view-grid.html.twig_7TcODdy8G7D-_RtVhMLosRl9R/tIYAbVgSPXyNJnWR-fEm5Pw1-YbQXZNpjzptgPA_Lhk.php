<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* themes/contrib/belgrade/templates/views/views-view-grid.html.twig */
class __TwigTemplate_4cc97f1da670d09b4719078401e95980 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 28
        $context["classes"] = ["views-view-grid", CoreExtension::getAttribute($this->env, $this->source,         // line 30
($context["options"] ?? null), "alignment", [], "any", false, false, true, 30), ("cols-" . CoreExtension::getAttribute($this->env, $this->source,         // line 31
($context["options"] ?? null), "columns", [], "any", false, false, true, 31)), "clearfix"];
        // line 35
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "row_class_default", [], "any", false, false, true, 35)) {
            // line 36
            yield "  ";
            // line 37
            $context["row_classes"] = ["views-row", (((CoreExtension::getAttribute($this->env, $this->source,             // line 39
($context["options"] ?? null), "alignment", [], "any", false, false, true, 39) == "horizontal")) ? ("clearfix") : ("")), "row"];
        }
        // line 44
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "col_class_default", [], "any", false, false, true, 44)) {
            // line 45
            yield "  ";
            // line 46
            $context["col_classes"] = ["views-col", (((CoreExtension::getAttribute($this->env, $this->source,             // line 48
($context["options"] ?? null), "alignment", [], "any", false, false, true, 48) == "vertical")) ? ("clearfix") : ("")), ("col-md-" . (12 / CoreExtension::getAttribute($this->env, $this->source,             // line 49
($context["options"] ?? null), "columns", [], "any", false, false, true, 49)))];
        }
        // line 53
        if (($context["title"] ?? null)) {
            // line 54
            yield "  <h3>";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true);
            yield "</h3>
";
        }
        // line 56
        yield "<div";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 56), "html", null, true);
        yield ">
  ";
        // line 57
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "alignment", [], "any", false, false, true, 57) == "horizontal")) {
            // line 58
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 59
                yield "      <div";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 59), "addClass", [($context["row_classes"] ?? null)], "method", false, false, true, 59), "html", null, true);
                yield ">
        ";
                // line 60
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "content", [], "any", false, false, true, 60));
                foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                    // line 61
                    yield "          <div";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["column"], "attributes", [], "any", false, false, true, 61), "addClass", [($context["col_classes"] ?? null)], "method", false, false, true, 61), "style"), "html", null, true);
                    yield ">";
                    // line 62
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["column"], "content", [], "any", false, false, true, 62), "html", null, true);
                    // line 63
                    yield "</div>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['column'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 65
                yield "      </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['row'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 67
            yield "  ";
        } else {
            // line 68
            yield "    ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["items"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
                // line 69
                yield "      <div";
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["column"], "attributes", [], "any", false, false, true, 69), "addClass", [($context["col_classes"] ?? null)], "method", false, false, true, 69), "html", null, true);
                yield ">
        ";
                // line 70
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["column"], "content", [], "any", false, false, true, 70));
                foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                    // line 71
                    yield "          <div";
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 71), "addClass", [($context["row_classes"] ?? null)], "method", false, false, true, 71), "html", null, true);
                    yield ">";
                    // line 72
                    yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["row"], "content", [], "any", false, false, true, 72), "html", null, true);
                    // line 73
                    yield "</div>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['row'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 75
                yield "      </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['column'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 77
            yield "  ";
        }
        // line 78
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["options", "title", "attributes", "items"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/views/views-view-grid.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  154 => 78,  151 => 77,  144 => 75,  137 => 73,  135 => 72,  131 => 71,  127 => 70,  122 => 69,  117 => 68,  114 => 67,  107 => 65,  100 => 63,  98 => 62,  94 => 61,  90 => 60,  85 => 59,  80 => 58,  78 => 57,  73 => 56,  67 => 54,  65 => 53,  62 => 49,  61 => 48,  60 => 46,  58 => 45,  56 => 44,  53 => 39,  52 => 37,  50 => 36,  48 => 35,  46 => 31,  45 => 30,  44 => 28,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/views/views-view-grid.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/themes/contrib/belgrade/templates/views/views-view-grid.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 28, "if" => 35, "for" => 58];
        static $filters = ["escape" => 54, "without" => 61];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['escape', 'without'],
                [],
                $this->source
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
