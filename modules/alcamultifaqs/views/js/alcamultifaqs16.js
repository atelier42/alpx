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
window.addEventListener('DOMContentLoaded', (e) => {
  // Evitar FAQ Home y Footer a la vez.
  /*var alcaMultifaqHome = document.getElementById('alcamultifaq-home');
  var alcaMultifaqFooter = document.getElementById('alcamultifaq-footer');

  if (alcaMultifaqHome != null && alcaMultifaqFooter != null) {
    if (alcaMultifaqHome.childNodes.length > 0 && alcaMultifaqFooter.childNodes.length > 0) {
      alcaMultifaqFooter.style.display = "none";
    }
  }*/

  // Cuando no usamos custom hook utilizamos displayFooter y movemos los elementos a la columna principal
  var columnsElement = document.getElementById('columns');
  var allAlcamultifaqs = document.querySelectorAll('.alcamultifaqs-container');

  allAlcamultifaqs.forEach((item) => {
    if (item.classList.contains('alcamultifaqs-move')) {
      columnsElement.appendChild(item);
    }
  });
  // -- 

  var acc = document.getElementsByClassName("alcamultifaq-accordion");
  var i;
  
  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("alcamultifaq-active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }
});