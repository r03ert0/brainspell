import java.io.File;
import java.io.IOException;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NamedNodeMap;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;


public class ModifyXMLFile {

	public static void main(String argv[]) {
	//DO NOT RUN AGAIN--ALEADY ADDED Z VALUES AND RUNNING AGAIN WOULD APPEND UNNECCESSARY EXTRA ZEROS

	/*
		  try {
				String filepath = "brainspell.xml";
				DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
				DocumentBuilder docBuilder = docFactory.newDocumentBuilder();
				Document doc = docBuilder.parse(filepath);

				//Loop through all experiments
				for (int j = 0; j < doc.getElementsByTagName("experiment").getLength(); j++) {

					Node paper1 = doc.getElementsByTagName("experiment").item(j);

					NodeList list = paper1.getChildNodes();

					for (int i = 0; i < list.getLength(); i++) {

			                   Node node = list.item(i);

					   // get the salary element, and update the value
					   if ("location".equals(node.getNodeName())) {
							 node.setTextContent(node.getTextContent() + ",0.0");
							 System.out.println(node.getTextContent());
					   }

					}
				}

				// write the content into xml file
				TransformerFactory transformerFactory = TransformerFactory.newInstance();
				Transformer transformer = transformerFactory.newTransformer();
				DOMSource source = new DOMSource(doc);
				StreamResult result = new StreamResult(new File(filepath));
				transformer.transform(source, result);

				System.out.println("Done");

		} catch (ParserConfigurationException pce) {
				pce.printStackTrace();
		} catch (TransformerException tfe) {
				tfe.printStackTrace();
		} catch (IOException ioe) {
				ioe.printStackTrace();
		} catch (SAXException sae) {
				sae.printStackTrace();
		}
		*/
	}

}
