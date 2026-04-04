<?php
/**
 * Bulk update 19 WordPress "project" CPT posts with new content, titles, slugs,
 * and Rank Math SEO metadata.
 *
 * Run:     ddev wp eval-file scripts/update-project-content.php
 * Dry run: DRY_RUN=1 ddev wp eval-file scripts/update-project-content.php
 */

$dry_run = getenv('DRY_RUN') === '1';

// Skip projects already updated manually in WP admin.
$skip_ids = [838, 1128, 37, 995, 1031, 974, 1068];

if ($dry_run) {
    echo "=== DRY RUN MODE ===\n\n";
} else {
    echo "=== LIVE UPDATE MODE ===\n\n";
}
echo "Skipping " . count($skip_ids) . " already-updated projects: " . implode(', ', $skip_ids) . "\n\n";

$projects = [

    // -------------------------------------------------------------------------
    // 1. Cold Turkey Cape Town
    // -------------------------------------------------------------------------
    [
        'id'                       => 1100,
        'post_title'               => 'Cold Turkey Cape Town',
        'post_name'                => 'cold-turkey-cape-town',
        'rank_math_title'          => 'Cold Turkey Cape Town — Powerful Documentary Photography of an Unforgettable Electronic Music Scene',
        'rank_math_description'    => 'Cold Turkey Cape Town was a bi-weekly electronic music event that brought Cape Town\'s most eclectic crowd together every second Sunday. Documentary photographs by Mads Nørgaard.',
        'rank_math_focus_keyword'  => 'Cold Turkey Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Cold Turkey Cape Town electronic music event crowd',
        'content'                  =>
            '<p>Cold Turkey Cape Town was a bi-weekly electronic music event held every second Sunday in Cape Town, South Africa. For several years during the late 2000s and early 2010s, Cold Turkey Cape Town operated as one of the city\'s most distinctive and consistently surprising gatherings for people interested in experimental beats, dubstep, electro and electronic music culture.</p>' .

            '<h2>The Electronic Music Scene Behind Cold Turkey Cape Town</h2>' .

            '<p>The Cold Turkey Cape Town events emerged at a time when the city\'s electronic music scene was evolving rapidly. Cape Town has always had a deep and often underappreciated relationship with dance music, particularly on the <a href="https://en.wikipedia.org/wiki/Cape_Town" target="_blank" rel="noopener noreferrer">Cape Flats, where house music</a> has been part of the social and cultural fabric since the 1990s. DJs from Mitchell\'s Plain, Manenberg and Bonteheuwel built a local sound that drew from Chicago, New York and Detroit while carrying its own identity. Cold Turkey Cape Town existed alongside that tradition while carving out a distinct space. The music leaned heavily on <a href="https://en.wikipedia.org/wiki/Dubstep" target="_blank" rel="noopener noreferrer">dubstep</a>, electro and anything beat-driven, with DJs regularly testing out unreleased tracks and productions that had not yet found a fixed genre. The focus was on experimentation rather than formula. Where the commercial club circuit in Long Street and Camps Bay catered to tourists and a narrow demographic, Cold Turkey Cape Town offered something different: a room where the music came first and the crowd followed.</p>' .

            '<h2>The Crowd and Culture of Cold Turkey Cape Town</h2>' .

            '<p>The crowd at Cold Turkey Cape Town was eclectic in the truest sense. In a city where nightlife often divides along racial, economic and geographic lines, these events managed to pull together a genuinely mixed room. People came from the southern suburbs, from the Cape Flats, from Woodstock and Observatory and further out. The atmosphere was inclusive without being self-conscious about it. Nobody needed to announce that the room was diverse. It just was. The collective behind Cold Turkey Cape Town also worked to provide a recreational and creative space for young people at a time when there were few venues in Cape Town willing to take risks on experimental electronic music. The events created a platform for emerging DJs and producers to test material in front of a receptive and knowledgeable audience, helping to incubate a generation of artists who would go on to shape the broader electronic music landscape in the city and beyond.</p>' .

            '<h2>A Record of Cold Turkey Cape Town</h2>' .

            '<p>The Cold Turkey Cape Town events eventually wound down, as these things tend to. Venues closed. People moved. The scene shifted. But the legacy of Cold Turkey Cape Town endures in the DJs, producers and music enthusiasts who passed through those Sunday sessions and carried the spirit of those rooms into other projects, labels and events across the city. What remains in these photographs is the energy of those sessions: the sweat, the bass, the movement of bodies when the sound was right and nobody was performing for anyone. The images capture a particular moment in Cape Town\'s nightlife history, before the gentrification of Woodstock and Observatory changed the character of the neighbourhoods where events like Cold Turkey Cape Town could happen, and before the city\'s electronic music scene gained wider international attention. Cold Turkey Cape Town was not trying to be historic. It was just a good night out. The photographs are a record of what that looked like.</p>' .

            '<p>Related projects: <a href="/proj/private-life-cape-town-house-music-disco-illusion">A Disco Illusion with Private Life</a>, <a href="/proj/red-bull-studios-cape-town-ctemf-opening">Red Bull Studios Cape Town CTEMF Opening</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 2. Handspring Puppet Company Cape Town
    // -------------------------------------------------------------------------
    [
        'id'                       => 1064,
        'post_title'               => 'Handspring Puppet Company Cape Town',
        'post_name'                => 'handspring-puppet-company-cape-town',
        'rank_math_title'          => 'Handspring Puppet Company Cape Town — Remarkable Documentary Photography for British Airways High Life',
        'rank_math_description'    => 'Handspring Puppet Company Cape Town documentary photography shot for British Airways High Life Magazine. Portraits of the acclaimed puppetry company founded by Adrian Kohler and Basil Jones.',
        'rank_math_focus_keyword'  => 'Handspring Puppet Company Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Handspring Puppet Company Cape Town workshop and artists',
        'content'                  =>
            '<p><a href="https://en.wikipedia.org/wiki/Handspring_Puppet_Company" target="_blank" rel="noopener noreferrer">Handspring Puppet Company</a> Cape Town was founded in 1981 by Artistic Director Adrian Kohler and Executive Producer Basil Jones. Over three decades, Handspring Puppet Company Cape Town built an extraordinary body of work that toured more than 30 countries and established the city as a global centre for puppetry arts, earning international acclaim for productions that moved between the intimate and the epic.</p>' .

            '<h2>The History of Handspring Puppet Company Cape Town</h2>' .

            '<p>The early years of Handspring Puppet Company Cape Town included collaborations with South African artist <a href="https://en.wikipedia.org/wiki/William_Kentridge" target="_blank" rel="noopener noreferrer">William Kentridge</a> that produced some of the most celebrated puppet theatre in the world. Those productions continue to tour internationally. In 1985, the company produced David Lytton\'s Episodes of an Easter Rising, their first work for adult audiences. This led to collaborations with directors including Esther van Ryswyk, Mark Fleishman, Malcolm Purkey and Barney Simon. Over the years, Handspring Puppet Company Cape Town also partnered with artists from across the African continent, including the Sogolon Puppet Troup from Mali and Koffi Koko from Benin, as well as international collaborators such as Tom Morris, Neil Bartlett and Khephra Burns. Their most recent production at the time of this shoot, Ouroboros, directed by Janni Younge, had been presented at the Baxter Theatre in Cape Town in 2011.</p>' .

            '<h2>Inside the Workshop of Handspring Puppet Company Cape Town</h2>' .

            '<p>By the time of this assignment, Handspring Puppet Company Cape Town had a full-time staff of more than 20 people and a roster of performing artists working across multiple productions. Their workshop and factory served simultaneously as a production facility and training ground. It was a space where performers, designers and technicians developed a physical language around puppetry that had little precedent on the continent. The workshop was part laboratory, part classroom, part factory floor, with puppets in various stages of construction visible alongside tools, fabrics and engineering drawings. The company was by then jointly directed by Adrian Kohler, Basil Jones and Janni Younge.</p>' .

            '<h2>Community Work of Handspring Puppet Company Cape Town</h2>' .

            '<p>The Handspring Trust for Puppetry Arts, a non-profit organisation established in 2010, extended the work of Handspring Puppet Company Cape Town beyond the stage and into communities across the Western Cape. The Trust operates out of premises in the informal settlement of Vrygrond, near the Handspring factory, and runs programmes to identify, mentor and champion the next generation of puppetry artists through workshops, academic engagement and community-based projects in rural areas and townships. The location in Vrygrond was deliberate, placing puppetry training directly within a community that would otherwise have no access to this kind of creative opportunity. The photographs in this series were shot on assignment for British Airways High Life Magazine and document the workshop, the puppets, the artists and the process of making work at a company that achieved international recognition while remaining deeply rooted in its home city of Cape Town.</p>' .

            '<p>Related projects: <a href="/proj/magnet-theatre-cape-town-youth-performance">Magnet Theatre Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 3. Johannesburg Documentary Photography
    // -------------------------------------------------------------------------
    [
        'id'                       => 1128,
        'post_title'               => 'Johannesburg Documentary Photography',
        'post_name'                => 'johannesburg-documentary-photography',
        'rank_math_title'          => 'Johannesburg Documentary Photography — Striking Images of the City, Weddings and Family Life',
        'rank_math_description'    => 'Johannesburg documentary photography capturing the city, weddings and family life across South Africa\'s largest and most restless metropolis. Photographs by Mads Nørgaard.',
        'rank_math_focus_keyword'  => 'Johannesburg documentary photography',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Johannesburg documentary photography street scene',
        'content'                  =>
            '<p>Johannesburg documentary photography requires a different approach to working in Cape Town. The two cities move at entirely different frequencies. Where Cape Town is shaped by the mountain and the ocean and the long distances between its historically separated communities, <a href="https://en.wikipedia.org/wiki/Johannesburg" target="_blank" rel="noopener noreferrer">Johannesburg</a> is dense, vertical and restless. It is a city built on gold mining and migrant labour, where wealth and poverty exist in closer physical proximity than almost anywhere else in South Africa.</p>' .

            '<h2>The Visual Language of Johannesburg Documentary Photography</h2>' .

            '<p>The Highveld light in Johannesburg is sharper and more direct than the coastal light of the Western Cape. It falls differently on buildings and faces, producing a visual quality that is harder and more immediate. For a photographer accustomed to Cape Town\'s softer, more diffused light, Johannesburg documentary photography demands a recalibration of exposure, contrast and timing. The city does not perform for the camera the way Cape Town sometimes does. Cape Town has Table Mountain, the Atlantic seaboard, the Winelands. Johannesburg\'s character reveals itself in motion: in the rhythm of its minibus taxis navigating the M1 and M2 highways, in the hawkers at traffic intersections, in the way people occupy space that is constantly being remade, demolished and rebuilt.</p>' .

            '<h2>Weddings, Family and City Life in Johannesburg Documentary Photography</h2>' .

            '<p>These Johannesburg documentary photography images were made over several visits to the city, spanning personal and professional occasions. Some were taken at weddings and family gatherings in the suburbs and townships. The wedding photographs capture moments of joy, tradition and community across South Africa\'s complex social landscape. Others are observations of the city itself: the street life, the architecture, the energy of a place that never quite lets you settle. Johannesburg is South Africa\'s economic engine and its largest city by population. The greater Gauteng metropolitan area is home to over 15 million people. The city\'s history is one of extraction: gold was discovered on the <a href="https://en.wikipedia.org/wiki/Witwatersrand_Gold_Rush" target="_blank" rel="noopener noreferrer">Witwatersrand in 1886</a>, and within years a mining camp had grown into one of the largest cities on the African continent. That extractive logic shaped everything that followed, from the migrant labour system to the racial zoning that divided the city into white suburbs and Black townships such as Soweto, Alexandra and Tembisa.</p>' .

            '<h2>Post-Apartheid Johannesburg Documentary Photography</h2>' .

            '<p>Post-apartheid Johannesburg has undergone significant transformation. Inner-city neighbourhoods like Braamfontein, Maboneng and Newtown have been regenerated, attracting artists, entrepreneurs and new residents. But the city\'s fundamental tensions remain. The gap between the northern suburbs and the townships is as wide as ever. Crime, unemployment and inequality continue to shape daily life for millions. This Johannesburg documentary photography series does not attempt to resolve those contradictions. It records what was visible during the time spent there: the street scenes, the family moments, the particular energy of a city that demands engagement entirely on its own terms. Each visit to Johannesburg is a recalibration for anyone used to photographing Cape Town. The visual language is different. The pace is different. You either catch it or you don\'t.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-public-transport-photography-exhibition-1">Exhibition 1 Cape Town</a>, <a href="/proj/blikkiesdorp-cape-town-n2-gateway-housing">Blikkiesdorp N2 Gateway</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 4. Exhibition 1 — Cape Town Public Transport Photography (PILLAR)
    // -------------------------------------------------------------------------
    [
        'id'                       => 1068,
        'post_title'               => 'Exhibition 1 — Cape Town Public Transport Photography',
        'post_name'                => 'cape-town-public-transport-photography-exhibition-1',
        'rank_math_title'          => 'Cape Town Public Transport Photography — Essential Seven-Year Documentary of Commuter Life',
        'rank_math_description'    => 'Cape Town public transport photography documenting seven years of daily commutes, bus stations, minibus taxis and the people who navigate a city shaped by apartheid spatial planning.',
        'rank_math_focus_keyword'  => 'Cape Town public transport photography',
        'rank_math_pillar_content' => 'on',
        'image_alt'                => 'Cape Town public transport photography commuters at station',
        'content'                  =>
            '<p>Cape Town public transport photography is at the heart of Exhibition 1, a documentary project that chronicles the daily movement of people through the city\'s bus, taxi and rail networks. The photographs were taken over seven years and focus on commuters stopping at stations, getting off minibus taxis and moving through the City Bowl and the southern suburbs of Cape Town.</p>' .

            '<h2>Apartheid\'s Legacy in Cape Town Public Transport Photography</h2>' .

            '<p>This Cape Town public transport photography project is concerned with three interconnected subjects: the daily commute and its rituals, the youth and how they navigate urban space, and the way that past and present spatial segregation continues to shape the city\'s people. Cape Town\'s public transport system is a direct inheritance of apartheid-era urban planning. The <a href="https://en.wikipedia.org/wiki/Group_Areas_Act" target="_blank" rel="noopener noreferrer">Group Areas Act</a> of 1950 forced Black and Coloured communities to the periphery of the city, creating vast distances between residential areas and places of work. The townships of Khayelitsha, Mitchell\'s Plain, Gugulethu, Nyanga, Langa and others were deliberately sited far from the economic centre. The intention was to keep Black labour available but invisible, housed at a distance that required hours of daily travel. Although the Group Areas Act was repealed in 1991, the spatial logic remains largely intact more than three decades later.</p>' .

            '<h2>Daily Commuters in Cape Town Public Transport Photography</h2>' .

            '<p>Commuters still travel enormous distances each day. A resident of Khayelitsha working in the City Bowl may spend three to four hours in transit daily, depending on traffic and the reliability of <a href="https://en.wikipedia.org/wiki/Golden_Arrow_Bus_Services" target="_blank" rel="noopener noreferrer">Golden Arrow buses</a>, <a href="https://en.wikipedia.org/wiki/Metrorail_(South_Africa)" target="_blank" rel="noopener noreferrer">Metrorail trains</a> or minibus taxis. The cost of transport consumes a disproportionate share of household income for working-class families on the Cape Flats. The Metrorail network has been plagued by cable theft, arson and infrastructure decay, leading to frequent cancellations and dangerously overcrowded services. In those hours of transit, people read, sleep, talk, eat, argue, flirt and wait. There is an ordinary intimacy to Cape Town public transport photography that this project seeks to capture. The bus or the taxi is one of the few spaces in the city where racial and economic divisions temporarily compress, where people from vastly different circumstances share a seat or a standing-room carriage for the duration of a journey.</p>' .

            '<h2>Exhibitions Featuring This Cape Town Public Transport Photography</h2>' .

            '<p>The Cape Town public transport photography in Exhibition 1 also documents the infrastructure itself: the stations, the platforms, the signage, the worn surfaces of places designed for function rather than comfort. These spaces carry their own visual language, marked by the passage of millions of commuters over years and decades. Exhibition 1 was shown as a solo exhibition at The Godden Gallery in Cape Town in 2016 and as part of the group exhibition Echoing Voices From Within at the University of Cape Town the same year. Earlier related work was exhibited at the AVA Gallery in 2012 as part of 3 and a Half Metres: Committee\'s Choice, as a solo show titled In Transit in 2012, and as part of the Bonani Africa 2010 Festival of Documentary Photography. This Cape Town public transport photography project remains an ongoing record of the city\'s commuter life and the distances that apartheid created and democracy has not yet closed.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-bus-terminus-after-dark">Cape Town Bus Terminus After Dark</a>, <a href="/proj/gentrification-woodstock-cape-town">Gentrification Woodstock Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 5. Gentrification Woodstock Cape Town (PILLAR)
    // -------------------------------------------------------------------------
    [
        'id'                       => 1031,
        'post_title'               => 'Gentrification Woodstock Cape Town',
        'post_name'                => 'gentrification-woodstock-cape-town',
        'rank_math_title'          => 'Gentrification Woodstock Cape Town — Devastating Documentary Photography of Displacement and Change',
        'rank_math_description'    => 'Documentary photography of gentrification Woodstock Cape Town and Salt River, capturing the displacement of long-standing residents as developers transform this historic neighbourhood.',
        'rank_math_focus_keyword'  => 'gentrification Woodstock Cape Town',
        'rank_math_pillar_content' => 'on',
        'image_alt'                => 'Gentrification Woodstock Cape Town street art and residents',
        'content'                  =>
            '<p>Gentrification Woodstock Cape Town is the subject of this documentary photography series, which captures the neighbourhood and the adjacent area of Salt River during a period of rapid and contested urban change. <a href="https://en.wikipedia.org/wiki/Woodstock,_Cape_Town" target="_blank" rel="noopener noreferrer">Woodstock</a> and Salt River sit on the eastern edge of the City Bowl, and their proximity to the centre of Cape Town has made them a focal point for property development and displacement since the early 2000s.</p>' .

            '<h2>The Apartheid History Behind Gentrification Woodstock Cape Town</h2>' .

            '<p>The story of gentrification Woodstock Cape Town cannot be understood without the neighbourhood\'s apartheid-era significance. Woodstock was one of the few areas in the city that survived the <a href="https://en.wikipedia.org/wiki/Group_Areas_Act" target="_blank" rel="noopener noreferrer">Group Areas Act</a> as a genuinely multiracial space. While the apartheid government forcibly removed communities across Cape Town to create racially segregated zones, Woodstock\'s mixed character proved difficult to eradicate entirely. Working-class Coloured, Black and White families lived alongside one another in a neighbourhood that the planners never quite managed to sort into a single racial category. Community resistance, in the form of the Open Woodstock campaign, played a significant role in halting enforced racial residential change during the 1980s and early 1990s.</p>' .

            '<h2>How Gentrification Woodstock Cape Town Accelerated</h2>' .

            '<p>After the transition to democracy in 1994, that same diversity and central location made the area attractive to investors. In the early 2000s, the National Treasury designated large parts of Woodstock as Urban Development Zones, offering tax incentives for the refurbishment and construction of commercial and residential properties. Developers such as Indigo Properties moved in and opened the Old Biscuit Mill and the Woodstock Exchange, curated spaces for food, design and the creative industries. These developments were marketed as renewal and urban regeneration. But the reality of gentrification Woodstock Cape Town was more complicated. Rents rose sharply. Long-standing residents, many of whom had lived in the area for decades and had successfully resisted forced removal under apartheid, found themselves being pushed out by market forces. Eviction notices were served. Families who could not afford the new rental prices were displaced to areas further from the city centre, repeating through economic rather than legislative mechanisms the same pattern of spatial exclusion that the Group Areas Act had enforced. Housing activist groups such as <a href="https://nu.org.za/" target="_blank" rel="noopener noreferrer">Ndifuna Ukwazi</a> documented and challenged these displacements through legal action and public advocacy.</p>' .

            '<h2>Documenting Gentrification Woodstock Cape Town</h2>' .

            '<p>Salt River, immediately adjacent to Woodstock, was beginning to follow the same trajectory. Historically a working-class industrial area with a strong Muslim community, its older buildings and relatively affordable rents attracted the same kind of developer interest. The pattern of gentrification Woodstock Cape Town was recognisable: creative industries move in, property values climb, original residents are displaced. These photographs document the neighbourhood during that transition. They capture the street life, the graffiti and public art, the faces of people who had been part of the area for generations, alongside the first visible signs of a different kind of removal. The series is a record of gentrification Woodstock Cape Town at a moment when the neighbourhood was caught between its history and its future, between the community that built it and the capital that was remaking it.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-public-transport-photography-exhibition-1">Exhibition 1 Cape Town Public Transport</a>, <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 6. Private Life Cape Town House Music
    // -------------------------------------------------------------------------
    [
        'id'                       => 995,
        'post_title'               => 'Private Life Cape Town House Music',
        'post_name'                => 'private-life-cape-town-house-music-disco-illusion',
        'rank_math_title'          => 'Private Life Cape Town House Music — Unforgettable Documentary Photography of Disco Illusion Nights',
        'rank_math_description'    => 'Documentary photography of Private Life Cape Town house music and disco nights, capturing the energy and atmosphere of the legendary Disco Illusion events on the Cape Town dancefloor.',
        'rank_math_focus_keyword'  => 'Private Life Cape Town house music',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Private Life Cape Town house music Disco Illusion dancefloor',
        'content'                  =>
            '<p>Private Life Cape Town house music nights were among the city\'s most respected gatherings for people who took dance music seriously. Drawing inspiration from the golden era of club culture and the modern sounds of leftfield house and disco, Private Life Cape Town house music events created a space that was deliberately intimate and musically uncompromising.</p>' .

            '<h2>The Deep Roots of Private Life Cape Town House Music</h2>' .

            '<p>The Private Life Cape Town house music events pulled from the deep end of the genre. This was not the commercial circuit. The lineage ran from the warehouses of New York and the basements of Chicago, through the early <a href="https://en.wikipedia.org/wiki/House_music" target="_blank" rel="noopener noreferrer">house and disco scenes</a> of the late 1970s and 1980s, and connected directly to the sound systems and community halls of the Cape Flats. That connection was central to what made Private Life Cape Town house music distinctive. On the Cape Flats, house music is not a trend or an import. It is embedded in the social fabric of Coloured and Black communities across Mitchell\'s Plain, Manenberg, Bonteheuwel, Hanover Park and beyond. House music is played at weddings, funerals, street parties and family gatherings. DJs from the Flats have been shaping the sound for decades, building a tradition that is distinct from but deeply connected to the global house music conversation. Many of these DJs have operated with little recognition beyond their own communities, despite maintaining a level of skill and knowledge that rivals any scene in the world.</p>' .

            '<h2>The Disco Illusion Nights of Private Life Cape Town House Music</h2>' .

            '<p>Private Life Cape Town house music understood and drew from that lineage. The events were not about spectacle or VIP culture. They were about the music and the people in the room. The Disco Illusion nights were among the most memorable events in the Private Life Cape Town house music calendar, bringing together DJs, dancers and music lovers in spaces where the focus was entirely on the dancefloor. The rooms were deliberately small. The sound systems were good. The programming was consistent.</p>' .

            '<h2>Photographing Private Life Cape Town House Music</h2>' .

            '<p>These photographs capture the atmosphere of one of those Disco Illusion evenings. The light is low. Bodies move through smoke and shadow. Faces are caught in that split second when a bassline drops and the room shifts together. The images document a specific night but also a broader culture of Private Life Cape Town house music: the city\'s deep relationship with dance music, the way the dancefloor functions as a social space, and the energy that emerges when a DJ, a sound system and a crowd are all working in the same direction. The Private Life <a href="https://en.wikipedia.org/wiki/Cape_Town" target="_blank" rel="noopener noreferrer">Cape Town</a> house music scene was part of a wider ecosystem of nights, collectives and DJs that kept the city\'s underground dance music culture alive during a period when gentrification and rising rents were transforming the neighbourhoods where these events took place.</p>' .

            '<p>Related projects: <a href="/proj/cold-turkey-cape-town">Cold Turkey Cape Town</a>, <a href="/proj/red-bull-studios-cape-town-ctemf-opening">Red Bull Studios Cape Town CTEMF</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 7. Danish Midsummer Sankt Hans
    // -------------------------------------------------------------------------
    [
        'id'                       => 974,
        'post_title'               => 'Danish Midsummer Sankt Hans',
        'post_name'                => 'danish-midsummer-sankt-hans-aarhus-copenhagen',
        'rank_math_title'          => 'Danish Midsummer Sankt Hans — Beautiful Documentary Photography from Aarhus and Copenhagen',
        'rank_math_description'    => 'Danish midsummer Sankt Hans Aften documentary photography from Aarhus and Copenhagen. Bonfires, traditions and the endless twilight of Scandinavia\'s longest night.',
        'rank_math_focus_keyword'  => 'Danish midsummer Sankt Hans',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Danish midsummer Sankt Hans bonfire celebrations Aarhus',
        'content'                  =>
            '<p>Danish midsummer <a href="https://en.wikipedia.org/wiki/Saint_John%27s_Eve#Denmark" target="_blank" rel="noopener noreferrer">Sankt Hans Aften</a> is celebrated across Denmark on the evening of 23 June each year. It is one of the country\'s oldest and most widely observed traditions, marking the summer solstice with bonfires, communal singing and gatherings in parks, on beaches and in public spaces as the year reaches its longest day.</p>' .

            '<h2>The Traditions of Danish Midsummer Sankt Hans</h2>' .

            '<p>The Danish midsummer Sankt Hans tradition dates back centuries and is named after Saint John the Baptist, whose feast day falls on 24 June. The centrepiece of the celebration is the bonfire, which is traditionally topped with a witch effigy. The burning of the witch is rooted in medieval superstition and the belief that fire would ward off evil spirits. In contemporary Denmark, the custom persists largely as ritual rather than belief, though it has periodically drawn criticism and debate. The most commonly sung song at Danish midsummer Sankt Hans gatherings is <a href="https://en.wikipedia.org/wiki/Holger_Drachmann" target="_blank" rel="noopener noreferrer">Holger Drachmann</a>\'s Vi elsker vort land, written in 1885, which celebrates the Danish landscape and national identity in language that most Danes have a complicated relationship with. The song is melancholic and patriotic in equal measure, and its annual performance at bonfires across the country functions as a kind of collective meditation on what it means to belong to a place.</p>' .

            '<h2>Danish Midsummer Sankt Hans in Aarhus and Copenhagen</h2>' .

            '<p>In Aarhus, Denmark\'s second largest city, people gathered at <a href="https://en.wikipedia.org/wiki/Godsbanen" target="_blank" rel="noopener noreferrer">Godsbanen</a> for the Danish midsummer Sankt Hans celebrations. Godsbanen is a former railway goods depot that has been converted into a cultural centre and creative hub, hosting events, workshops and performances throughout the year. The bonfire drew families, students and residents from across the city. In Copenhagen, Danish midsummer Sankt Hans celebrations took place along the harbour and in parks across the capital, with bonfires lit at locations including Amager Strandpark and various harbour-front sites. The atmosphere at both gatherings was a mix of the ceremonial and the casual, with children running around the fire and adults standing with drinks, watching the flames and singing the old songs.</p>' .

            '<h2>Returning Home for Danish Midsummer Sankt Hans</h2>' .

            '<p>These photographs of Danish midsummer Sankt Hans were made after several years spent living in the Southern Hemisphere, where the seasons are reversed and midsummer falls in December. Returning to Denmark for the celebrations carries a particular quality of recognition and distance. The fire, the songs, the specific quality of Scandinavian summer twilight that stretches past eleven at night without ever becoming fully dark. In Cape Town, where much of the preceding decade had been spent, the winter solstice in June brings early darkness and cold fronts rolling in off the Atlantic. The contrast is total. Danish midsummer Sankt Hans is a homecoming of sorts, even when the idea of home has become more complicated than it used to be. The photographs capture both the communal and the personal dimensions of the evening: the crowds around the fire, the light on faces, and the sky that refuses to go dark.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-public-transport-photography-exhibition-1">Exhibition 1 Cape Town</a>, <a href="/proj/cold-turkey-cape-town">Cold Turkey Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 8. Joining Hands Tafelsig Mitchell's Plain
    // -------------------------------------------------------------------------
    [
        'id'                       => 911,
        'post_title'               => 'Joining Hands Tafelsig Mitchell\'s Plain',
        'post_name'                => 'joining-hands-tafelsig-mitchells-plain',
        'rank_math_title'          => 'Joining Hands Tafelsig Mitchell\'s Plain — Inspiring Documentary Photography of Community Resilience',
        'rank_math_description'    => 'Documentary photography of Joining Hands Tafelsig Mitchell\'s Plain, the community organisation transforming their streets through youth programmes, soup kitchens and sports days.',
        'rank_math_focus_keyword'  => 'Joining Hands Tafelsig Mitchell\'s Plain',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Joining Hands Tafelsig Mitchell\'s Plain community sports day',
        'content'                  =>
            '<p>Joining Hands Tafelsig Mitchell\'s Plain is a non-profit community-based organisation founded by residents of Theronsberg and Voelvlei streets in the Tafelsig section of <a href="https://en.wikipedia.org/wiki/Mitchells_Plain" target="_blank" rel="noopener noreferrer">Mitchell\'s Plain</a>, Cape Town. The organisation was formed in direct response to a lack of constructive activities for the children and young people of the area, and it operates as a locally elected body dedicated to improving conditions on their streets through practical, community-led interventions.</p>' .

            '<h2>What Makes Joining Hands Tafelsig Mitchell\'s Plain Different</h2>' .

            '<p>Walking through the streets where Joining Hands Tafelsig Mitchell\'s Plain operates, the difference is immediately noticeable. There is a visible sense of care. Children move freely on their way to the community field. People greet you. Despite the hardship endured by the families living here, there is a spirit of purpose and pride that sets these streets apart from the surrounding blocks. Mitchell\'s Plain was established in the 1970s as a housing area for Coloured families displaced under the <a href="https://en.wikipedia.org/wiki/Group_Areas_Act" target="_blank" rel="noopener noreferrer">Group Areas Act</a>. It sits approximately 32 kilometres from the Cape Town city centre and is one of the largest townships in the Western Cape, with a population of over 300,000. The area has long struggled with overcrowding, unemployment, gangsterism and substance abuse. In Tafelsig, one of the township\'s poorest sections, these pressures are particularly acute.</p>' .

            '<h2>The Work of Joining Hands Tafelsig Mitchell\'s Plain</h2>' .

            '<p>Joining Hands Tafelsig Mitchell\'s Plain addresses these challenges through a combination of practical interventions. The organisation runs a soup kitchen that provides daily meals to children. They host sports days on the community field, which volunteers cleared of rubble and rubbish before filling with soil and establishing a community garden. Library days are organised at the Tafelsig Library. The organisation provides a base for social workers and runs employment and sustainability projects. Three donated shipping containers were converted into a community centre by local builders who volunteered their labour. On sports days, the field fills with children playing soccer and netball on ground that was previously dumping space. The older generation participates actively, modelling for the youth what it looks like to take responsibility for your own environment.</p>' .

            '<h2>Supporting Joining Hands Tafelsig Mitchell\'s Plain</h2>' .

            '<p>Joining Hands Tafelsig Mitchell\'s Plain has achieved a remarkable amount with extremely limited resources. Without recreational infrastructure or youth programmes provided by the state, young residents in the area are vulnerable to recruitment by gangs and to cycles of drug use and violence. The work of Joining Hands Tafelsig Mitchell\'s Plain demonstrates what is possible when a community decides to organise from within rather than waiting for external intervention. What the organisation needs to sustain and expand its programmes is ongoing financial support for education, recreation and youth development. The committee has the vision, the local knowledge and the commitment. What they lack is funding. These photographs document the people, the projects and the daily life of the streets where Joining Hands Tafelsig Mitchell\'s Plain operates: soup being served, fields being cleared, children playing, and the faces of residents reshaping their own neighbourhood.</p>' .

            '<p>Related projects: <a href="/proj/100-homes-project-beacon-valley-mitchells-plain">100 Homes Project Beacon Valley</a>, <a href="/proj/passion-gap-cape-flats-dental-modification">Passion Gap Cape Flats</a>, <a href="/proj/blikkiesdorp-cape-town-n2-gateway-housing">Blikkiesdorp N2 Gateway</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 9. Cape Town Bus Terminus After Dark (PILLAR)
    // -------------------------------------------------------------------------
    [
        'id'                       => 886,
        'post_title'               => 'Cape Town Bus Terminus After Dark',
        'post_name'                => 'cape-town-bus-terminus-after-dark',
        'rank_math_title'          => 'Cape Town Bus Terminus — Powerful Documentary Photography of Commuter Life After Dark',
        'rank_math_description'    => 'Documentary photography of the Cape Town bus terminus and Golden Arrow station after dark. Capturing the quiet hours of commuter life on the Cape Flats. Photos by Mads Nørgaard.',
        'rank_math_focus_keyword'  => 'Cape Town bus terminus',
        'rank_math_pillar_content' => 'on',
        'image_alt'                => 'Cape Town bus terminus Golden Arrow commuters after dark',
        'content'                  =>
            '<p>The Cape Town bus terminus on Charl Malan Street is the primary hub for the <a href="https://en.wikipedia.org/wiki/Golden_Arrow_Bus_Services" target="_blank" rel="noopener noreferrer">Golden Arrow</a> public bus network. Every working day, hundreds of thousands of commuters travel through the Cape Town bus terminus from the townships of the Cape Flats into the city centre and back again.</p>' .

            '<h2>Apartheid Geography and the Cape Town Bus Terminus</h2>' .

            '<p>The Cape Town bus terminus serves areas including Khayelitsha, Mitchell\'s Plain, Gugulethu, Nyanga, Philippi, Delft, Langa, Bonteheuwel and Manenberg. The distances between these townships and the city centre are enormous, a direct consequence of <a href="https://en.wikipedia.org/wiki/Urban_apartheid" target="_blank" rel="noopener noreferrer">apartheid-era urban planning</a> that deliberately placed Black and Coloured residential areas as far as possible from the white economic centre. The Group Areas Act of 1950 created a spatial geography designed to keep labour available but communities separated. That geography has not meaningfully changed. In peak hour traffic, the journey from Khayelitsha to the Cape Town bus terminus can take well over an hour. From Mitchell\'s Plain the travel time is similarly long. This means that many commuters leave home before first light, arriving at the Cape Town bus terminus before dawn, and catch the bus home only after dark. The cost of a monthly bus pass consumes a significant portion of household income, yet Golden Arrow remains one of the more reliable forms of public transport in the city.</p>' .

            '<h2>The Quiet Hours at the Cape Town Bus Terminus</h2>' .

            '<p>The atmosphere at the Cape Town bus terminus in the early morning and late evening hours is markedly different from the chaos of peak time. The platforms are quiet. A few commuters sit reading the newspaper or resting between shifts. Others stand alone, waiting for a connection, thinking. There is a stillness in these hours that contrasts sharply with the relentless cycle of movement that defines the rest of the day at the Cape Town bus terminus. The long hours of work go by and the buses fill again going home, and the cycle repeats the next day, and the next.</p>' .

            '<h2>Photographing the Cape Town Bus Terminus</h2>' .

            '<p>These photographs were made during those liminal hours at the Cape Town bus terminus, at a place most of the city\'s wealthier residents have never visited and will never need to. The terminus exists in the daily experience of hundreds of thousands of people but is invisible to the Cape Town that lives in the southern suburbs, the Atlantic seaboard and the northern suburbs. The images capture the light at the Cape Town bus terminus, the expressions on faces, the posture of tired bodies, and the quiet routines that fill the time between arriving and departing. The bus terminus is not a destination. It is a point of passage. But for the people who move through the Cape Town bus terminus every day, it is also a space of stillness inside a system designed to keep people moving.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-public-transport-photography-exhibition-1">Exhibition 1 Cape Town Public Transport Photography</a>, <a href="/proj/blikkiesdorp-cape-town-n2-gateway-housing">Blikkiesdorp N2 Gateway</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 10. Red Bull Studios Cape Town
    // -------------------------------------------------------------------------
    [
        'id'                       => 867,
        'post_title'               => 'Red Bull Studios Cape Town CTEMF Opening',
        'post_name'                => 'red-bull-studios-cape-town-ctemf-opening',
        'rank_math_title'          => 'Red Bull Studios Cape Town — Exciting Documentary Photography of the CTEMF Opening Night',
        'rank_math_description'    => 'Documentary photography of the Red Bull Studios Cape Town opening night and pre-CTEMF event featuring Diplo, Jazzy Jay and local Cape Town DJs. Photos by Mads Nørgaard.',
        'rank_math_focus_keyword'  => 'Red Bull Studios Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Red Bull Studios Cape Town opening night DJ performance',
        'content'                  =>
            '<p>Red Bull Studios Cape Town opened on Bree Street in the city centre with an event that doubled as a warm-up for the <a href="https://en.wikipedia.org/wiki/Cape_Town_Electronic_Music_Festival" target="_blank" rel="noopener noreferrer">Cape Town Electronic Music Festival</a>, known as CTEMF. The opening of Red Bull Studios Cape Town signalled the city\'s growing profile as a centre for music production on the African continent.</p>' .

            '<h2>International and Local Artists at Red Bull Studios Cape Town</h2>' .

            '<p>Among the guests at the Red Bull Studios Cape Town opening were American DJ and producer <a href="https://en.wikipedia.org/wiki/Diplo" target="_blank" rel="noopener noreferrer">Diplo</a> and legendary hip-hop DJ and producer <a href="https://en.wikipedia.org/wiki/Jazzy_Jay" target="_blank" rel="noopener noreferrer">Jazzy Jay</a>, a founding member of the Universal Zulu Nation and one of the earliest figures in hip-hop history. Jazzy Jay\'s pioneering work alongside Afrika Bambaataa in the South Bronx during the late 1970s and early 1980s helped shape the foundations of hip-hop culture worldwide. His presence at the Red Bull Studios Cape Town event connected the city\'s contemporary electronic music scene to the broader global lineage of DJ culture. Local DJs Thibo Tazz, Mr Sakitumi and the Girl, and Christian Tiger School played sets throughout the evening at Red Bull Studios Cape Town. Their inclusion on the lineup alongside international headliners reflected a commitment to showcasing local talent on an equal footing with global acts.</p>' .

            '<h2>Cape Town\'s Electronic Music Scene and Red Bull Studios Cape Town</h2>' .

            '<p>Cape Town\'s electronic music community has always run deeper than its international profile might suggest. On the Cape Flats, house music has been a central part of social and cultural life for decades. DJs and producers from townships and suburbs across the Flats have built a sound that is distinct from but connected to the global electronic music conversation. The Cape Town Electronic Music Festival, CTEMF, was for the years it ran one of the few events that brought the local scene into direct conversation with international artists and audiences, showcasing music from deep house to techno to experimental beat production. Red Bull Studios Cape Town provided recording and production space for local and visiting artists at a time when access to professional studio facilities remained limited for many musicians from disadvantaged communities.</p>' .

            '<h2>Documenting the Red Bull Studios Cape Town Opening</h2>' .

            '<p>These photographs document the opening night at Red Bull Studios Cape Town: the DJs behind the decks, the crowd on the dancefloor, the energy of a room where local and international acts shared the same space. The images capture a specific moment in the evolution of Cape Town\'s electronic music landscape, at a time when the city was gaining wider recognition as a destination for music production, performance and collaboration. Red Bull Studios Cape Town represented an investment in infrastructure for a scene that had been building for decades, largely without institutional support.</p>' .

            '<p>Related projects: <a href="/proj/cold-turkey-cape-town">Cold Turkey Cape Town</a>, <a href="/proj/private-life-cape-town-house-music-disco-illusion">Private Life Cape Town House Music</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 11. Magnet Theatre Cape Town Youth Performance
    // -------------------------------------------------------------------------
    [
        'id'                       => 849,
        'post_title'               => 'Magnet Theatre Cape Town Youth Performance',
        'post_name'                => 'magnet-theatre-cape-town-youth-performance',
        'rank_math_title'          => 'Magnet Theatre Cape Town — Inspiring Documentary Photography of Youth Performance from the Townships',
        'rank_math_description'    => 'Documentary photography of Magnet Theatre Cape Town youth performances featuring young performers from Philippi and Khayelitsha townships. Physical theatre and community development.',
        'rank_math_focus_keyword'  => 'Magnet Theatre Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Magnet Theatre Cape Town youth performers on stage',
        'content'                  =>
            '<p><a href="https://magnettheatre.co.za/" target="_blank" rel="noopener noreferrer">Magnet Theatre</a> Cape Town has been creating performance opportunities for young people from the city\'s most under-resourced townships since the early 2000s. Based in the Old Match Factory in Woodstock, Magnet Theatre Cape Town is an independent performance company and training facility founded by Mark Fleishman, Jennie Reznek and Mandla Mbothwe.</p>' .

            '<h2>Youth Development at Magnet Theatre Cape Town</h2>' .

            '<p>The youth performance work at Magnet Theatre Cape Town focuses on physical theatre, devised performance and community engagement. The company has a particular commitment to young people from areas where access to the performing arts is virtually nonexistent. Schools across the Cape Flats rarely have arts programmes of any kind. Drama, dance and music education are considered luxuries in communities where basic educational infrastructure is often inadequate. Magnet Theatre Cape Town exists to fill that gap, providing structured training and creative opportunity to young people who would otherwise have none.</p>' .

            '<h2>Performers from Philippi and Khayelitsha at Magnet Theatre Cape Town</h2>' .

            '<p>The young performers in these photographs came from Philippi and <a href="https://en.wikipedia.org/wiki/Khayelitsha" target="_blank" rel="noopener noreferrer">Khayelitsha</a>, two of the largest and most densely populated townships in the Cape Town metropolitan area. Khayelitsha was established in 1985 during the apartheid era as a relocation area for Black residents and now has a population estimated at over 400,000. Philippi faces similar challenges: high unemployment, overcrowding and extremely limited recreational or cultural facilities. Magnet Theatre Cape Town\'s Culture Gangs programme works directly with young people from these communities, running monthly workshops, holiday programmes and theatre visits. The programme\'s aim is to create gangs committed to culture rather than crime, a deliberate framing in areas where gang recruitment begins at primary school age.</p>' .

            '<h2>The Showcase at Magnet Theatre Cape Town</h2>' .

            '<p>At the end of each cycle, a showcase is held at Magnet Theatre Cape Town where the participants perform for an audience. For many, it is the first time they have been on a stage. The showcase is the culmination of months of consistent work: learning to use the body as an instrument, developing the confidence to perform in front of strangers, and collaborating with peers. Since 2008, Magnet Theatre Cape Town has produced close to 100 graduates, many of whom have gone on to university education and professional careers in the arts. These photographs document one of the showcases at Magnet Theatre Cape Town: the performers on stage, the concentration on their faces, the physicality of the work, and the moment of stepping into a spotlight that represents a world many of them did not know existed.</p>' .

            '<p>Related projects: <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig Mitchell\'s Plain</a>, <a href="/proj/handspring-puppet-company-cape-town">Handspring Puppet Company Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 12. Blikkiesdorp Cape Town N2 Gateway Housing (PILLAR)
    // -------------------------------------------------------------------------
    [
        'id'                       => 838,
        'post_title'               => 'Blikkiesdorp Cape Town N2 Gateway Housing',
        'post_name'                => 'blikkiesdorp-cape-town-n2-gateway-housing',
        'rank_math_title'          => 'Blikkiesdorp Cape Town — Heartbreaking Documentary Photography of the N2 Gateway Housing Crisis',
        'rank_math_description'    => 'Documentary photography of Blikkiesdorp Cape Town, the temporary relocation settlement in Delft where thousands of families wait for permanent housing on the Cape Flats.',
        'rank_math_focus_keyword'  => 'Blikkiesdorp Cape Town',
        'rank_math_pillar_content' => 'on',
        'image_alt'                => 'Blikkiesdorp Cape Town tin structures and residents',
        'content'                  =>
            '<p><a href="https://en.wikipedia.org/wiki/Blikkiesdorp" target="_blank" rel="noopener noreferrer">Blikkiesdorp</a> Cape Town is the popular name for the Symphony Way Temporary Relocation Area in Delft, on the northern outskirts of the city. The name Blikkiesdorp is Afrikaans for Tin Town, a reference to the rows of identical corrugated iron structures that make up the settlement. Blikkiesdorp Cape Town was established as part of the <a href="https://en.wikipedia.org/wiki/N2_Gateway" target="_blank" rel="noopener noreferrer">N2 Gateway housing project</a>, launched in 2005 to address the severe housing shortage in the Western Cape.</p>' .

            '<h2>The Housing Crisis Behind Blikkiesdorp Cape Town</h2>' .

            '<p>The scale of the <a href="https://en.wikipedia.org/wiki/Housing_in_South_Africa" target="_blank" rel="noopener noreferrer">housing crisis</a> that Blikkiesdorp Cape Town was meant to alleviate is staggering. At the time of these photographs, an estimated 430,000 families were on the waiting list for a house in the Western Cape province. The provincial budget for housing construction stood at approximately R350 million per year, enough to build around 9,000 houses annually. At that rate, the backlog would take decades to clear. The people waiting for permanent housing were placed in temporary settlements like Blikkiesdorp Cape Town. The word temporary lost its meaning quickly. Many residents had been living in Blikkiesdorp Cape Town for three years or more, with no clear timeline for permanent accommodation. The settlement had an estimated 10,000 residents and was still expanding.</p>' .

            '<h2>Living Conditions in Blikkiesdorp Cape Town</h2>' .

            '<p>The conditions in Blikkiesdorp Cape Town are harsh. The Cape Flats are exposed to strong south-easterly winds in summer and cold, wet winters. The corrugated iron structures offer minimal insulation. Sanitation facilities are shared. Access to clean water, electricity and healthcare is limited. Crime, particularly gender-based violence, is a serious concern. The layout of Blikkiesdorp Cape Town, with its uniform rows and minimal public space, was designed for efficiency rather than livability.</p>' .

            '<h2>Community and Resilience in Blikkiesdorp Cape Town</h2>' .

            '<p>Despite these conditions, the residents of Blikkiesdorp Cape Town built a community. Small shops opened. Children attended local schools. Street committees were formed to manage safety and resolve disputes. Entrepreneurship took root under circumstances that would discourage most people. Blikkiesdorp Cape Town sits alongside the N2 highway, visible from the road that connects Cape Town International Airport to the city centre. For most visitors, it is a blur of tin seen from a car window. For the 10,000 people who live there, it is home. These photographs document both the material reality of Blikkiesdorp Cape Town and the resilience of the people within it: the children playing, the families outside their homes, the small acts of normalcy that persist in conditions the state created and then largely abandoned.</p>' .

            '<p>Related projects: <a href="/proj/cape-town-bus-terminus-after-dark">Cape Town Bus Terminus After Dark</a>, <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig Mitchell\'s Plain</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 13. Passion Gap Cape Flats (PILLAR)
    // -------------------------------------------------------------------------
    [
        'id'                       => 820,
        'post_title'               => 'Passion Gap Cape Flats',
        'post_name'                => 'passion-gap-cape-flats-dental-modification',
        'rank_math_title'          => 'Passion Gap Cape Flats — Fascinating Documentary Photography of Dental Modification in Cape Town',
        'rank_math_description'    => 'Documentary photography exploring the Passion Gap Cape Flats tradition of dental modification in working-class Coloured communities across Mitchell\'s Plain and greater Cape Town.',
        'rank_math_focus_keyword'  => 'Passion Gap Cape Flats',
        'rank_math_pillar_content' => 'on',
        'image_alt'                => 'Passion Gap Cape Flats portrait showing dental modification',
        'content'                  =>
            '<p>The <a href="https://en.wikipedia.org/wiki/Passion_gap" target="_blank" rel="noopener noreferrer">Passion Gap</a> Cape Flats tradition of deliberately extracting healthy upper front teeth has been a common practice in working-class Coloured communities across Cape Town for more than sixty years. Known also as the Cape Flats Smile or Pap Bek, the Passion Gap Cape Flats phenomenon is a deeply localised form of body modification occurring primarily in the townships and suburbs of the Cape Flats.</p>' .

            '<h2>Origins and Meaning of the Passion Gap Cape Flats Tradition</h2>' .

            '<p>The origins of the Passion Gap Cape Flats practice are debated. Many people explain it as a fashion statement. Others say it improves kissing or enhances oral sex. Peer pressure among teenagers is a significant driver. Gangsterism has also played a role, with the Passion Gap Cape Flats modification carrying specific cultural meanings related to group identity. What is consistent is that the practice functions as a marker of identity, particularly among the poorest working class in Coloured areas of South Africa. A 2003 <a href="https://www.uct.ac.za/" target="_blank" rel="noopener noreferrer">University of Cape Town</a> study surveyed 2,167 Coloured respondents in the Western Cape and found that 41% had had their front teeth extracted. Of those, 44.8% were male. Children as young as eleven have undergone the procedure for aesthetic reasons. The Passion Gap Cape Flats practice is rare among the middle class and virtually nonexistent among the wealthy. Approximately 75% of people with a Passion Gap identify as Coloured.</p>' .

            '<h2>Perspectives on the Passion Gap Cape Flats Modification</h2>' .

            '<p>Jacqui Friedling, an anthropologist at the University of Cape Town, has noted that some individuals with the Passion Gap Cape Flats modification use multiple sets of dentures: plain white teeth for work and gold or gem sets for social occasions, functioning as a display of status. Ryan Müller, a Capetonian dentist who grew up in <a href="https://en.wikipedia.org/wiki/Mitchells_Plain" target="_blank" rel="noopener noreferrer">Mitchell\'s Plain</a>, no longer performs the extractions at his clinic. Gavin van Sensie, a Tafelsig resident who lost his front teeth after a soccer injury, says he would never advise his son to do the same.</p>' .

            '<h2>Historical Roots of the Passion Gap Cape Flats Practice</h2>' .

            '<p>The Passion Gap Cape Flats phenomenon resists easy interpretation. It is part fashion, part class signifier, part tradition that has outlived its original context. Its roots may stretch back centuries. Dental modification in Southern Africa has been documented for at least 1,500 years. During the mid-seventeenth century, enslaved people at the Cape Colony sometimes removed their own teeth as an act of bodily autonomy. Whether a direct line connects those acts to the contemporary Passion Gap Cape Flats practice is unclear, but the resonance is difficult to ignore. These photographs document the people behind the statistics: the faces, the smiles, the gold teeth and the gaps, and the community in which the Passion Gap Cape Flats tradition continues to hold meaning.</p>' .

            '<p>Related projects: <a href="/proj/100-homes-project-beacon-valley-mitchells-plain">100 Homes Project Beacon Valley</a>, <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig Mitchell\'s Plain</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 14. Al-Quds Day Cape Town
    // -------------------------------------------------------------------------
    [
        'id'                       => 803,
        'post_title'               => 'Al-Quds Day Cape Town',
        'post_name'                => 'al-quds-day-cape-town-palestine-solidarity',
        'rank_math_title'          => 'Al-Quds Day Cape Town — Powerful Documentary Photography of Palestine Solidarity on the Streets',
        'rank_math_description'    => 'Documentary photography of Al-Quds Day Cape Town, the annual Palestine solidarity march through the streets of Cape Town led by the city\'s Muslim community. Photos by Mads Nørgaard.',
        'rank_math_focus_keyword'  => 'Al-Quds Day Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Al-Quds Day Cape Town Palestine solidarity march at Parliament',
        'content'                  =>
            '<p>Al-Quds Day Cape Town takes place annually on the last Friday of Ramadan, when hundreds of members of the city\'s Muslim community gather in front of the South African Parliament. Al-Quds is the Arabic name for Jerusalem and means "The Holy One." The Al-Quds Day Cape Town march is observed as a day of humanitarian solidarity with the people of Palestine.</p>' .

            '<h2>History and Context of Al-Quds Day Cape Town</h2>' .

            '<p><a href="https://en.wikipedia.org/wiki/Quds_Day" target="_blank" rel="noopener noreferrer">International Quds Day</a> was initiated in 1979 and is commemorated worldwide. The Al-Quds Day Cape Town march draws on a long tradition of political mobilisation within the city\'s Muslim community, concentrated in the <a href="https://en.wikipedia.org/wiki/Bo-Kaap" target="_blank" rel="noopener noreferrer">Bo-Kaap</a> and across the Cape Flats. The Cape Muslim community\'s history of resistance predates the end of apartheid. <a href="https://en.wikipedia.org/wiki/Abdullah_Haron" target="_blank" rel="noopener noreferrer">Imam Abdullah Haron</a>, imam of the Al-Jamia Mosque in Claremont, was killed in detention by the security police in 1969. Achmad Cassiem spent years as a political prisoner on Robben Island. These figures are part of a lineage that connects anti-apartheid struggle with international solidarity. Cassiem addressed the Al-Quds Day Cape Town crowd and called for unity among Muslims experiencing oppression worldwide.</p>' .

            '<h2>On the Streets of Al-Quds Day Cape Town</h2>' .

            '<p>Marchers at Al-Quds Day Cape Town carried banners and posters with slogans including "Down with Zionism" and "Free Palestine." Speakers who had travelled from Palestine addressed the gathering. Children were prominently present throughout Al-Quds Day Cape Town: girls on their fathers\' shoulders holding signs, boys alongside the vehicles passing through the streets. An Israeli flag was burned in front of Parliament. The atmosphere at Al-Quds Day Cape Town combined the familial with the political. Entire families attended, with grandparents, parents and children marching together.</p>' .

            '<h2>Solidarity and Community at Al-Quds Day Cape Town</h2>' .

            '<p>Al-Quds Day Cape Town is one of the most visible annual expressions of the Cape Muslim community\'s longstanding support for Palestinian self-determination. Several marchers wore T-shirts quoting Imam Husain: "Death with dignity is better than life with humiliation." The march passed through central Cape Town before returning to Parliament for final speeches. These photographs document the Al-Quds Day Cape Town march itself: the crowd, the banners, the speeches, the children, and the energy of a community that understands solidarity not as abstraction but as lived practice rooted in its own history of resistance and survival.</p>' .

            '<p>Related projects: <a href="/proj/gentrification-woodstock-cape-town">Gentrification Woodstock Cape Town</a>, <a href="/proj/cape-town-public-transport-photography-exhibition-1">Exhibition 1 Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 15. DRIVE for Excellence Charity Golf Day
    // -------------------------------------------------------------------------
    [
        'id'                       => 776,
        'post_title'               => 'DRIVE for Excellence Charity Golf Day',
        'post_name'                => 'drive-for-excellence-charity-golf-day-south-africa',
        'rank_math_title'          => 'DRIVE for Excellence Charity Golf Day — Inspiring Documentary Photography of Community Fundraising in South Africa',
        'rank_math_description'    => 'Documentary photography of the DRIVE for Excellence charity golf day, a platform that has raised over R2.2 million for education and youth development in disadvantaged South African communities.',
        'rank_math_focus_keyword'  => 'DRIVE for Excellence charity golf day',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'DRIVE for Excellence charity golf day players and sponsors',
        'content'                  =>
            '<p>The DRIVE for Excellence charity golf day is an annual fundraising event established to foster positive change in disadvantaged communities across South Africa. The initiative focuses on education, healthcare, sports development and youth development, and by the time of their sixth event, the DRIVE for Excellence charity golf day had raised approximately R2.2 million.</p>' .

            '<h2>The Purpose of the DRIVE for Excellence Charity Golf Day</h2>' .

            '<p>The DRIVE for Excellence charity golf day directs funds toward programmes and organisations working in communities where the gap between available resources and need is vast. South Africa has one of the highest levels of <a href="https://en.wikipedia.org/wiki/Poverty_in_South_Africa" target="_blank" rel="noopener noreferrer">income inequality</a> in the world. Youth unemployment regularly exceeds 40%. In this context, platforms like the DRIVE for Excellence charity golf day serve a specific function: they connect corporate resources with community needs, creating a pipeline of funding for organisations working on the ground.</p>' .

            '<h2>How the DRIVE for Excellence Charity Golf Day Works</h2>' .

            '<p>The DRIVE for Excellence charity golf day format brings together corporate sponsors, business leaders and community supporters for a day of sport and fundraising. The events feature fourballs, prize-givings, auction items and networking opportunities. Sponsors contribute both financially and through in-kind support. The DRIVE for Excellence charity golf day works to ensure that funds raised translate into measurable outcomes in education and youth development rather than remaining in the sphere of corporate hospitality.</p>' .

            '<h2>Documenting the DRIVE for Excellence Charity Golf Day</h2>' .

            '<p>Charity golf days are a common fundraising mechanism in South Africa, where <a href="https://en.wikipedia.org/wiki/Corporate_social_responsibility" target="_blank" rel="noopener noreferrer">corporate social investment</a> intersects with sport. They are sometimes criticised for the distance between the course and the communities being served. The DRIVE for Excellence charity golf day addresses this by maintaining clear accountability and directing resources toward specific, tangible programmes. These photographs document the sixth annual DRIVE for Excellence charity golf day as it unfolded: the players on the course, the sponsors, the organisers and volunteers, and the broader atmosphere of an event designed to move resources toward the communities that need them most.</p>' .

            '<p>Related projects: <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig Mitchell\'s Plain</a>, <a href="/proj/100-homes-project-beacon-valley-mitchells-plain">100 Homes Project Beacon Valley</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 16. 100 Homes Project Beacon Valley
    // -------------------------------------------------------------------------
    [
        'id'                       => 772,
        'post_title'               => '100 Homes Project Beacon Valley',
        'post_name'                => '100-homes-project-beacon-valley-mitchells-plain',
        'rank_math_title'          => '100 Homes Project Beacon Valley — Compelling Documentary Photography of Community and Poverty in Mitchell\'s Plain',
        'rank_math_description'    => 'Documentary photography of the 100 Homes Project Beacon Valley, a community survey by Baitul Ansaar Child Care Centre addressing poverty and food insecurity in Mitchell\'s Plain.',
        'rank_math_focus_keyword'  => '100 Homes Project Beacon Valley',
        'rank_math_pillar_content' => '',
        'image_alt'                => '100 Homes Project Beacon Valley family portrait Mitchell\'s Plain',
        'content'                  =>
            '<p>The 100 Homes Project Beacon Valley grew out of a question asked by the staff at <a href="https://www.baitulansaar.org" target="_blank" rel="noopener noreferrer">Baitul Ansaar</a> Child and Youth Care Centre in Mitchell\'s Plain: why do fostered children keep coming back? The 100 Homes Project Beacon Valley was the response, a community survey and intervention that would change how the centre understood and addressed the needs of the families around it.</p>' .

            '<h2>Origins of the 100 Homes Project Beacon Valley</h2>' .

            '<p>Baitul Ansaar, established in 2008, is a non-profit organisation providing residential care for approximately 42 children in Beacon Valley, <a href="https://en.wikipedia.org/wiki/Mitchells_Plain" target="_blank" rel="noopener noreferrer">Mitchell\'s Plain</a>. It is the largest child and youth care centre of its kind in Cape Town. When children were placed with foster families nearby, the placements frequently broke down. Managing director Bushra Razack initiated the 100 Homes Project Beacon Valley to understand why. In partnership with University of the Western Cape medical students, the centre surveyed 100 homes in the Beacon Valley neighbourhood, asking about finances, food security, employment and daily challenges.</p>' .

            '<h2>Findings of the 100 Homes Project Beacon Valley</h2>' .

            '<p>The findings of the 100 Homes Project Beacon Valley survey were stark. Many households did not have food during the last week of the month. Foster parents could not afford to feed an additional child. The problem was structural poverty, not neglect. In a community where household budgets are stretched to breaking and formal employment is scarce, the welfare system\'s assumption that foster families can absorb the costs of care was fundamentally flawed. The 100 Homes Project Beacon Valley revealed that the welfare of a child cannot be separated from the welfare of the household, and the household cannot be separated from the community.</p>' .

            '<h2>Interventions from the 100 Homes Project Beacon Valley</h2>' .

            '<p>The 100 Homes Project Beacon Valley led to practical interventions. Rather than operating solely within the walls of the centre, Baitul Ansaar extended into the community. A food garden was established. A <a href="https://en.wikipedia.org/wiki/Community_exchange_system" target="_blank" rel="noopener noreferrer">community exchange programme</a> was launched, allowing residents to trade skills and goods outside the cash economy. The model of the 100 Homes Project Beacon Valley recognised that conventional welfare approaches are not enough in a community like Beacon Valley, where the formal economy excludes the majority of residents. Mitchell\'s Plain was established in the 1970s for Coloured families displaced under the Group Areas Act and has one of the highest violent crime rates in the Western Cape.</p>' .

            '<h2>Photographing the 100 Homes Project Beacon Valley</h2>' .

            '<p>These photographs were taken during visits to each of the 100 homes identified by the 100 Homes Project Beacon Valley. They are portraits of families, interiors and the material conditions of daily life in one of Mitchell\'s Plain\'s most under-resourced areas. The images of the 100 Homes Project Beacon Valley do not sensationalise. They document the homes as they are, and the people who live in them, with the understanding that this project is a community looking inward and taking care of its own.</p>' .

            '<p>Related projects: <a href="/proj/joining-hands-tafelsig-mitchells-plain">Joining Hands Tafelsig Mitchell\'s Plain</a>, <a href="/proj/passion-gap-cape-flats-dental-modification">Passion Gap Cape Flats</a>, <a href="/proj/blikkiesdorp-cape-town-n2-gateway-housing">Blikkiesdorp Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 17. Dookoom Cape Town
    // -------------------------------------------------------------------------
    [
        'id'                       => 58,
        'post_title'               => 'Dookoom Cape Town',
        'post_name'                => 'dookoom-cape-town-south-african-hip-hop',
        'rank_math_title'          => 'Dookoom Cape Town — Explosive Documentary Photography of South Africa\'s Most Controversial Hip-Hop Group',
        'rank_math_description'    => 'Documentary photography of Dookoom Cape Town, the controversial hip-hop group fronted by Isaac Mutant. Early portraits and press shots featured by Vice and Noisey.',
        'rank_math_focus_keyword'  => 'Dookoom Cape Town',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Dookoom Cape Town Isaac Mutant live performance',
        'content'                  =>
            '<p>Dookoom Cape Town emerged in the early 2010s as one of the most confrontational and politically charged musical acts to come out of the Western Cape. Fronted by Isaac Mutant, Dookoom Cape Town takes its name from the figure of the doekoem, a powerful and feared character from <a href="https://en.wikipedia.org/wiki/Cape_Malay" target="_blank" rel="noopener noreferrer">Cape Muslim mysticism</a>.</p>' .

            '<h2>The Politics Behind Dookoom Cape Town</h2>' .

            '<p>Dookoom Cape Town arrived at a moment when the promise of 1994 had visibly stalled. Land reform remained largely unimplemented. Farm workers in the Western Cape continued to labour on land they would never own. The <a href="https://en.wikipedia.org/wiki/2012%E2%80%932013_Western_Cape_farm_workers%27_strike" target="_blank" rel="noopener noreferrer">farm worker strikes of 2012 and 2013</a> in the De Doorns area brought national attention to conditions in the agricultural sector, but little changed structurally. Dookoom Cape Town took that reality and turned it into noise, channelling the anger, absurdity and unresolved contradictions of post-apartheid South Africa into music that was impossible to ignore.</p>' .

            '<h2>Early Days of Dookoom Cape Town</h2>' .

            '<p>The working relationship with Dookoom Cape Town began in 2013 at their first performance at a small venue called Blitzkrug, later known as Lefties. A few photographs were taken of frontman Isaac Mutant. The collaboration grew into band portraits and press shots, some of which were featured by Noisey US, Noisey France and numerous other media. The most controversial work from Dookoom Cape Town was the music video for "Larney Jou Poes," in which farm workers ride a tractor across fields before burning the word DOOKOOM into a hillside. According to academic Adam Haupt, the imagery invoked the doekoem figure to signify revolt.</p>' .

            '<h2>The Impact of Dookoom Cape Town</h2>' .

            '<p>The video from Dookoom Cape Town provoked outrage in Afrikaner farming communities and was pulled from YouTube after threats and complaints. But it had already made its point. Dookoom Cape Town named, without qualification, a rage that had been building for generations among the working class on the farms of the Western Cape. These photographs are from the early days of Dookoom Cape Town, before the controversy and the headlines: a band in a small venue, making music that had not yet found its audience but already carried the weight of everything it would come to represent.</p>' .

            '<p>Related projects: <a href="/proj/cold-turkey-cape-town">Cold Turkey Cape Town</a>, <a href="/proj/gentrification-woodstock-cape-town">Gentrification Woodstock Cape Town</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 18. Ayanda Mabulu Artist
    // -------------------------------------------------------------------------
    [
        'id'                       => 37,
        'post_title'               => 'Ayanda Mabulu Artist Studio Cape Town',
        'post_name'                => 'ayanda-mabulu-artist-studio-cape-town',
        'rank_math_title'          => 'Ayanda Mabulu Artist — Extraordinary Studio Documentary Photography in Woodstock, Cape Town',
        'rank_math_description'    => 'Studio documentary photography of Ayanda Mabulu artist in Woodstock, Cape Town. Portraits of the self-taught painter known for provocative political works and hyper-realistic imagery.',
        'rank_math_focus_keyword'  => 'Ayanda Mabulu artist',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Ayanda Mabulu artist working in Woodstock studio Cape Town',
        'content'                  =>
            '<p><a href="https://en.wikipedia.org/wiki/Ayanda_Mabulu" target="_blank" rel="noopener noreferrer">Ayanda Mabulu</a> artist is a self-taught visual artist born in 1981 in the Eastern Cape, South Africa. The work of Ayanda Mabulu artist deals in provocation as method, using large-scale paintings built from acrylic, oil, gold leaf, textiles and found materials to confront structures of power and exploitation.</p>' .

            '<h2>The Practice of Ayanda Mabulu Artist</h2>' .

            '<p>The paintings of Ayanda Mabulu artist are executed in a hyper-realistic style and frequently depict political figures, including former president Jacob Zuma, in compromising positions. These works have led to censorship, outcry and death threats. His 2012 painting "Economy Rape" provoked a national controversy and forced Ayanda Mabulu artist to relocate his studio. But the practice extends beyond political caricature. The "Healers" series celebrates Black women in South African society. Sculptural work and playwriting explore themes of power, identity and the politics of the Black body.</p>' .

            '<h2>International Recognition of Ayanda Mabulu Artist</h2>' .

            '<p>Ayanda Mabulu artist has exhibited internationally, including a solo exhibition at the <a href="https://en.wikipedia.org/wiki/DuSable_Museum_of_African_American_History" target="_blank" rel="noopener noreferrer">DuSable Museum of African American History</a> in Chicago in 2018, titled "Troublemaker: Art Is Our Only Hope." His work has been covered by the New York Times, BBC and Al Jazeera. At auction, the 2018 portrait Nontsundu by Ayanda Mabulu artist sold in 2021 for approximately $27,900. He currently lives and works in Johannesburg at the Victoria Yards creative hub.</p>' .

            '<h2>Studio Portraits of Ayanda Mabulu Artist in Woodstock</h2>' .

            '<p>These photographs of Ayanda Mabulu artist were made in his studio in Woodstock, Cape Town, in 2013. He was working in a small space surrounded by canvases in various stages of completion. Gold leaf and paint covered every surface. The photographs capture Ayanda Mabulu artist at work before the major museum shows, the international headlines and the auction results. Before the gallery representation and the coverage. Just a painter in a room in Woodstock, making work that would not let South Africa look away from itself. These images of Ayanda Mabulu artist document the beginning of a practice that has since become one of the most significant and provocative in contemporary South African art.</p>' .

            '<p>Related projects: <a href="/proj/gentrification-woodstock-cape-town">Gentrification Woodstock Cape Town</a>, <a href="/proj/rhodes-must-fall-uct-statue-removal-cape-town">Rhodes Must Fall UCT</a></p>',
    ],

    // -------------------------------------------------------------------------
    // 19. Rhodes Must Fall UCT
    // -------------------------------------------------------------------------
    [
        'id'                       => 88,
        'post_title'               => 'Rhodes Must Fall UCT',
        'post_name'                => 'rhodes-must-fall-uct-statue-removal-cape-town',
        'rank_math_title'          => 'Rhodes Must Fall UCT — Historic Documentary Photography of the Statue Removal, 9 April 2015',
        'rank_math_description'    => 'Documentary photography of the Rhodes Must Fall UCT statue removal on 9 April 2015. The removal of the Cecil John Rhodes monument at the University of Cape Town.',
        'rank_math_focus_keyword'  => 'Rhodes Must Fall UCT',
        'rank_math_pillar_content' => '',
        'image_alt'                => 'Rhodes Must Fall UCT statue removal crane and crowd',
        'content'                  =>
            '<p><a href="https://en.wikipedia.org/wiki/Rhodes_Must_Fall" target="_blank" rel="noopener noreferrer">Rhodes Must Fall</a> UCT culminated on 9 April 2015, when a crane lifted the bronze statue of <a href="https://en.wikipedia.org/wiki/Cecil_Rhodes" target="_blank" rel="noopener noreferrer">Cecil John Rhodes</a> from the upper campus of the University of Cape Town. The Rhodes Must Fall UCT movement had shaken the foundations of South African higher education and ignited a national conversation about colonial memory and decolonisation.</p>' .

            '<h2>The Colonial History Behind Rhodes Must Fall UCT</h2>' .

            '<p>Cecil John Rhodes, at the centre of the Rhodes Must Fall UCT campaign, made his fortune in diamond and gold mining. He served as Prime Minister of the Cape Colony from 1890 to 1896 and was the architect of the <a href="https://en.wikipedia.org/wiki/Glen_Grey_Act" target="_blank" rel="noopener noreferrer">Glen Grey Act</a> of 1894, legislation that laid the groundwork for the migrant labour system that would become apartheid. He founded the De Beers mining company and the territory of Rhodesia. His statue had stood at UCT since 1934. The Rhodes Must Fall UCT campaign began on 9 March 2015 when student Chumani Maxwele threw a bucket of human excrement at the monument, drawing attention to the fact that many Cape Flats residents still lacked proper sanitation while the university honoured a figure responsible for their dispossession.</p>' .

            '<h2>The Movement Beyond Rhodes Must Fall UCT</h2>' .

            '<p>Rhodes Must Fall UCT quickly expanded beyond the question of the statue. Students raised broader questions about transformation: the Eurocentric curriculum, language policies, institutional culture, and who the post-apartheid university was actually designed to serve. The UCT Council voted on 27 March 2015 to remove the statue. Thousands gathered to watch on 9 April. Some celebrated. Others debated. The Rhodes Must Fall UCT moment became a catalyst for the nationwide <a href="https://en.wikipedia.org/wiki/FeesMustFall" target="_blank" rel="noopener noreferrer">Fees Must Fall</a> movement later that year, demanding free, decolonised higher education across South Africa.</p>' .

            '<h2>Documenting Rhodes Must Fall UCT</h2>' .

            '<p>These photographs document the events of 9 April 2015 at the heart of the Rhodes Must Fall UCT movement: the crowd on the steps, the crane at work, the empty plinth, and the faces of the people who came to witness a symbol of colonial power being removed from one of Africa\'s most prestigious universities. The images record a moment that was both an ending and a beginning, a day when the Rhodes Must Fall UCT campaign achieved its most visible demand and opened questions that South African institutions continue to grapple with.</p>' .

            '<p>Related projects: <a href="/proj/ayanda-mabulu-artist-studio-cape-town">Ayanda Mabulu Artist Studio Cape Town</a>, <a href="/proj/gentrification-woodstock-cape-town">Gentrification Woodstock Cape Town</a></p>',
    ],

];

// ---------------------------------------------------------------------------
// Process each project
// ---------------------------------------------------------------------------
$updated = 0;
$skipped = 0;

foreach ($projects as $project) {
    // Skip projects already updated manually
    if (in_array($project['id'], $skip_ids, true)) {
        echo "SKIP (manual): {$project['post_title']} (ID {$project['id']})\n";
        $skipped++;
        continue;
    }

    // Get existing content to preserve gallery shortcode
    $existing = get_post($project['id']);
    if (!$existing) {
        echo "SKIP: Post {$project['id']} not found\n";
        $skipped++;
        continue;
    }

    // Extract gallery shortcode(s) from existing content
    $gallery = '';
    if (preg_match_all('/\[gallery[^\]]*\]/', $existing->post_content, $matches)) {
        $gallery = "\n\n" . implode("\n\n", $matches[0]);
    }
    // Also preserve mauer-stills gallery blocks
    if (preg_match_all('/<div class="mauer-stills-gallery-pswp-wrapper">.*?<\/div>\s*<\/div>/s', $existing->post_content, $ms)) {
        $gallery .= "\n\n" . implode("\n\n", $ms[0]);
    }
    // Also preserve wp-block-gallery
    if (preg_match_all('/(<figure class="wp-block-gallery[^"]*".*?<\/figure>)/s', $existing->post_content, $ms)) {
        $gallery .= "\n\n" . implode("\n\n", $ms[0]);
    }

    $full_content = $project['content'] . $gallery;

    if ($dry_run) {
        echo "DRY RUN: Would update '{$project['post_title']}' (ID {$project['id']}, slug: {$project['post_name']})\n";
        echo "  Old title: {$existing->post_title}\n";
        echo "  New title: {$project['post_title']}\n";
        echo "  Old slug:  {$existing->post_name}\n";
        echo "  New slug:  {$project['post_name']}\n";
        echo "  SEO Title: {$project['rank_math_title']}\n";
        echo "  Focus KW:  {$project['rank_math_focus_keyword']}\n";
        echo "  Pillar:    " . ($project['rank_math_pillar_content'] === 'on' ? 'YES' : 'no') . "\n";
        echo "  Content length: " . strlen($full_content) . " chars\n";
        echo "  Gallery preserved: " . (empty($gallery) ? 'no' : 'yes') . "\n\n";
        continue;
    }

    // Update post
    $result = wp_update_post([
        'ID'           => $project['id'],
        'post_title'   => $project['post_title'],
        'post_name'    => $project['post_name'],
        'post_content' => $full_content,
    ], true);

    if (is_wp_error($result)) {
        echo "ERROR: Failed to update '{$project['post_title']}' (ID {$project['id']}): {$result->get_error_message()}\n";
        $skipped++;
        continue;
    }

    // Update Rank Math meta
    update_post_meta($project['id'], 'rank_math_title', $project['rank_math_title']);
    update_post_meta($project['id'], 'rank_math_description', $project['rank_math_description']);
    update_post_meta($project['id'], 'rank_math_focus_keyword', $project['rank_math_focus_keyword']);

    if (!empty($project['rank_math_pillar_content'])) {
        update_post_meta($project['id'], 'rank_math_pillar_content', 'on');
    } else {
        delete_post_meta($project['id'], 'rank_math_pillar_content');
    }

    // Update featured image alt text
    $thumb_id = get_post_thumbnail_id($project['id']);
    if ($thumb_id && !empty($project['image_alt'])) {
        update_post_meta($thumb_id, '_wp_attachment_image_alt', $project['image_alt']);
    }

    echo "Updated: {$project['post_title']} (ID {$project['id']}, slug: {$project['post_name']})\n";
    $updated++;
}

echo "\nDone. " . count($projects) . " projects processed";
if ($dry_run) {
    echo " (dry run - no changes made)";
} else {
    echo " ({$updated} updated, {$skipped} skipped)";
}
echo ".\n";
