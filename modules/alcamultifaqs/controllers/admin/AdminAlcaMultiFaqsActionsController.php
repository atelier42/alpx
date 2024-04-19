<?php
/**
 * 2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * @author ALCALINK E-COMMERCE & SEO, S.L.L. <info@alcalink.com>
 * @copyright  2023 ALCALINK E-COMMERCE & SEO, S.L.L.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Registered Trademark & Property of ALCALINK E-COMMERCE & SEO, S.L.L.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminAlcaMultiFaqsActionsController extends ModuleAdminController
{
    public function postProcess()
    {
        return parent::postProcess();
    }

    /**
     * Devolver IDs del tipo de menú (Categorías, Páginas o Productos)
     *
     * @return json
     */
    public function ajaxProcessGetIdsType()
    {
        $type = Tools::getValue('type');
        $ids = AlcaMultiFaq::getIdsByType($type);

        $resp = [];

        if ($ids) {
            if (count($ids) > 0) {
                $resp['ids'] = $ids;
            }
        }

        echo json_encode($resp);

        exit;
    }

    /**
     * Actualizar posición
     *
     * @return void
     */
    public function ajaxProcessChangePosition()
    {
        $positions = Tools::getValue('positions');
        $results = [];

        foreach ($positions as $k => $pos) {
            $results[$k] = [
                'id_alcamultifaqs' => (int) $k,
                'position' => (int) $pos,
            ];
        }

        $context = Context::getContext();
        $id_shop = $context->shop->id;

        AlcaMultiFaq::changePosition($results, $id_shop);
    }

    /**
     * Get lista FAQ
     *
     * @return json
     */
    public function ajaxProcessGetFilterList()
    {
        $type = Tools::getValue('type');
        $id_object = (int) Tools::getValue('id_object');

        $resp = [];
        $resp = AlcaMultiFaq::getAjaxStructuredFaq($type, $id_object);
        echo json_encode($resp);

        exit;
    }

    /**
     * Eliminar FAQ
     *
     * @return json
     */
    public function ajaxProcessDeleteFaq()
    {
        $id = (int) Tools::getValue('id_alcamultifaqs');
        $type = Tools::getValue('type');
        $id_object = Tools::getValue('id_object') ? Tools::getValue('id_object') : null;
        $resp = [];

        if ($id && $type) {
            $alcamultifaq = new AlcaMultiFaq($id);

            if ($alcamultifaq) {
                $alcamultifaq_aux = $alcamultifaq;

                if ($alcamultifaq->delete()) {
                    $module = Module::getInstanceByName('alcamultifaqs');
                    $module->clearCacheObject($alcamultifaq_aux);

                    $resp = AlcaMultiFaq::getAjaxStructuredFaq($type, $id_object);
                }
            }
        }

        echo json_encode($resp);

        exit;
    }
}
