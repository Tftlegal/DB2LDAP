<?php
    require_once('./php/pages/1_introduction.php');
    require_once('./php/pages/2_login.php');
    require_once('./php/pages/3_control.php');
    require_once('./php/pages/4_conclusion.php');

    abstract class Page {
        public abstract static function getTitle(): string;
        public abstract static function render(): void;
    }

    define('PAGES', [
        1 => IntroductionPage::class,
        2 => LoginPage::class,
        3 => ControlPage::class,
        4 => ConclusionPage::class
    ]);

    class PageService {

        public static function render(): void {
            $pageId = self::getPageId();

            self::renderBreadcrumbs($pageId);
            self::renderContent($pageId);
        }

        private static function renderBreadcrumbs(int $pageId): void {
?>
            <nav class="bg-body-tertiary" style="--bs-breadcrumb-divider: '>';">
                <div class="container">
                    <ol class="breadcrumb justify-content-center">
                        <?php for ($i = 1; $i <= count(PAGES); ++$i) { ?>
                            <li class="breadcrumb-item <?php if ($pageId == $i) echo('text-ci-primary'); ?>"><?php echo(PAGES[$i]::getTitle()); ?></li>
                        <?php } ?>
                    </ol>
                </div>
            </nav>
<?php
        }

        private static function renderContent(int $pageId): void {
?>
            <div class="bg-body">
                <div class="container">
                    <?php PAGES[$pageId]::render(); ?>
                </div>

                <div class="container mt-4 d-flex justify-content-center">
                    <?php self::renderNavigation($pageId); ?>
                </div>
            </div>
<?php
        }

        private static function renderNavigation(int $pageId): void {
?>
            <div class="container mt-4 d-flex justify-content-center">
                <?php if ($pageId > 1) { ?>
                    <a href="?page=<?php echo($pageId - 1); ?>" type="button" class="btn btn-secondary m-1"><i class="bi bi-arrow-left"></i> Vorige Seite</a>
                <?php } ?>
                <?php if ($pageId < COUNT(PAGES)) { ?>
                    <a href="?page=<?php echo($pageId + 1); ?>" type="button" class="btn btn-success m-1">Nächste Seite <i class="bi bi-arrow-right"></i></a>
                <?php } ?>
            </div>
<?php
        }

        private static function getPageId(): int {
            $pageId = $_GET['page'] ?? 1;

            if ($pageId < 1) {
                $pageId = 1;
            } else if (count(PAGES) < $pageId) {
                $pageId = count(PAGES);
            }

            return $pageId;
        }

    }

?>